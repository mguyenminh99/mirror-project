<?php

require getenv('PROJECT_ROOT') . '/app/autoload.php';
require_once(getenv('PROJECT_ROOT') . '/prod/docker/app/setup/Config/Config.php');
require_once(getenv('PROJECT_ROOT') . '/prod/docker/app/setup/Helper/Mail.php');

$setup = new Setup();
$setup->init();

class Setup{

    protected $bootstrap;
    protected $objectManager;
    protected $configWriter;
    protected $logger;
    protected $config;
    protected $mail;

    function __construct(){
        $this->bootstrap     = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
        $this->objectManager = $this->bootstrap->getObjectManager();
        $this->configWriter  = $this->objectManager->create(\Magento\Framework\App\Config\Storage\WriterInterface::class);
        $this->logger        = $this->objectManager->create(\Magento\Framework\Logger\Monolog::class, ['name' => 'init_script']);
        $this->config        = new Config();
        $this->mail          = new Mail();
    }

    public function init(){

        try{

            $this->logger->info('Start init-script.');

            if ( $this->isXssInitialized() ) {
                $this->logger->info('Finish init-script, container is already setup.');
                exit(0);
            }
            $connection = mysqli_connect($this->config->DB_HOST, $this->config->DB_USER, $this->config->DB_PASS);

            if (!$connection) {
                throw new Error(mysqli_connect_error(), $this->logger->ERROR);
            }

            $db = mysqli_select_db($connection, $this->config->DB_NAME);

            if (!$db) {
                throw new Error("Database {$this->config->DB_HOST} does not exist.");
            }

            if (mysqli_query($connection, "SELECT 1 FROM `core_config_data` LIMIT 0")) {

                $this->executeCommand("php {$this->config->PROJECT_ROOT}/bin/magento setup:upgrade");
                $this->executeCommand("php {$this->config->PROJECT_ROOT}/bin/magento setup:static-content:deploy -f");

            } else {

                $this->logger->info('Start installing Magento.');

                // installation fails if env.php exists before installation process begins
                if( file_exists($this->config->PROJECT_ROOT . '/app/etc/env.php' ) ){
                    unlink($this->config->PROJECT_ROOT . '/app/etc/env.php' );
                }

                $commandInstall = "php -f {$this->config->PROJECT_ROOT}/bin/magento setup:install --base-url=http://{$this->config->HOST_NAME} --base-url-secure=https://{$this->config->HOST_NAME} --db-host={$this->config->DB_HOST} --db-name={$this->config->DB_NAME}  --db-user={$this->config->DB_USER} --db-password={$this->config->DB_PASS} --admin-firstname={$this->config->ADMIN_FIRSTNAME} --admin-lastname={$this->config->ADMIN_LASTNAME} --admin-email={$this->config->ADMIN_MAIL} --admin-user={$this->config->ADMIN_USER} --admin-password={$this->config->ADMIN_PASS} --language=ja_JP --currency=JPY --timezone=Asia/Tokyo --backend-frontname={$this->config->ADMIN_PAGE_SLUG} --use-secure=1 --use-secure-admin=1";
                $this->executeCommand($commandInstall);

                // overwrite env.php with env.php.tpl using env vars for DB info and hostname
                copy($this->config->PROJECT_ROOT . '/app/etc/env.php.tpl', $this->config->PROJECT_ROOT . '/app/etc/env.php');

                $this->saveVarnishConfig();

                $this->logger->info('Installing Magento successfully.');
            }

            fopen($this->config->PROJECT_ROOT . "/init.done", "w");

            $this->logger->info('Finish init-script.');

        }catch (Error $err){
            $this->errorHandler($err);
        }
    }

    private function saveVarnishConfig(){
        $configValue = [
            ['path' => 'system/full_page_cache/caching_application' , 'value' => 2],
            ['path' => 'system/full_page_cache/varnish/access_list' , 'value' => 'localhost'],
            ['path' => 'system/full_page_cache/varnish/backend_host', 'value' => 'localhost'],
            ['path' => 'system/full_page_cache/varnish/backend_port', 'value' => 8080],
            ['path' => 'system/full_page_cache/varnish/grace_period', 'value' => 300],
        ];

        foreach ($configValue as $config) {
            $this->configWriter->save($config['path'], $config['value'], 'default', 0);
        }
    }

    private function isXssInitialized(){
        return file_exists(getenv('PROJECT_ROOT') . "/init.done");
    }

    private function executeCommand($command){

        exec($command, $output, $resultCode);

        if ($resultCode != 0) {
            $errorContent = is_array($output) ? implode(PHP_EOL, $output) : $output;
            throw new Error($errorContent);
        }

        $this->logger->info(is_array($output) ? implode(PHP_EOL, $output) : $output);
    }

    private function errorHandler(Error $err){

        $this->logger->error($err->getMessage());

        $this->mail->send($err->getMessage());

        exit(1);
    }
}
