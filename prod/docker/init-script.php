<?php

echo 'Start executing init-script.php.'.PHP_EOL;
if (isXssInitialized()) {
    echo 'Finish executing init-script.php since container is already setup.'.PHP_EOL;
    exit(1);
}
$rootDirectory = getenv('PROJECT_ROOT');

require $rootDirectory . 'app/autoload.php';
require_once('Zend/Mail/Transport/Smtp.php');
require_once 'Zend/Mail.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$filesystem = $objectManager->create(\Magento\Framework\Filesystem::class);
$configWriter = $objectManager->create(\Magento\Framework\App\Config\Storage\WriterInterface::class);

$mysqlHost = getenv('DB_HOST');
$mysqlDbName = getenv('DB_NAME');
$mysqlUser = getenv('DB_USER');
$mysqlPass = getenv('DB_PASS');
$hostname = getenv('HOST_NAME');
$adminFirstName = getenv('ADMIN_FIRSTNAME');
$adminLastName = getenv('ADMIN_LASTNAME');
$adminEmail = getenv('ADMIN_MAIL');
$adminUser = getenv('ADMIN_USER');
$adminPass = getenv('ADMIN_PASS');
$adminPageSlug = getenv('ADMIN_PAGE_SLUG');

$connection = mysqli_connect($mysqlHost, $mysqlUser, $mysqlPass);
if (!$connection) {
    sendErrorEmail('Cannot connect to database server', 'Connect database');
    echo 'Error : ' . mysqli_connect_error();
    exit(1);
}

$dbSelected = mysqli_select_db($connection, $mysqlDbName);

if (!$dbSelected) {
    echo 'Database "' . $mysqlDbName . '" not exist!' . PHP_EOL;
    sendErrorEmail('Database "' . $mysqlDbName . '" not exist!');
    exit(1);
}

if (mysqli_query($connection, "SELECT 1 FROM `core_config_data` LIMIT 0")) {

    executeCommand("php {$rootDirectory}bin/magento setup:upgrade");
    executeCommand("php {$rootDirectory}bin/magento setup:static-content:deploy -f");

} else {

    echo 'Starting install Magento' . PHP_EOL;

    $etcPathFolder = $filesystem->getDirectoryWrite('etc')->getAbsolutePath();
    unlink($etcPathFolder . 'env.php');

    echo 'Remove env.php' . PHP_EOL;

    $commandRemoveFolder = "rm -rf generated/code/ && rm -rf pub/static/deployed_version.txt && rm -rf var/cache/ var/page_cache/ var/view_preprocessed/";
    executeCommand($commandRemoveFolder);

    echo 'Remove cache/ generated/ pub/static folder' . PHP_EOL;

    $commandInstall = "php -f {$rootDirectory}bin/magento setup:install --base-url=http://{$hostname} --base-url-secure=https://{$hostname} --db-host={$mysqlHost} --db-name={$mysqlDbName}  --db-user={$mysqlUser} --db-password={$mysqlPass} --admin-firstname={$adminFirstName} --admin-lastname={$adminLastName} --admin-email={$adminEmail} --admin-user={$adminUser} --admin-password={$adminPass} --language=ja_JP --currency=JPY --timezone=Asia/Tokyo --backend-frontname={$adminPageSlug} --use-secure=1 --use-secure-admin=1";
    executeCommand($commandInstall);

    copy($etcPathFolder . 'env.php.tpl', $etcPathFolder . 'env.php');

    saveVarnishConfig($configWriter, $hostname);

    echo 'Install Magento successfully' . PHP_EOL;
    fopen($rootDirectory . "init.done", "w");
}

function saveVarnishConfig($configWriter,$hostname)
{
    $configValue = [
        ['path' => 'system/full_page_cache/caching_application', 'value' => 2],
        ['path' => 'system/full_page_cache/varnish/access_list', 'value' => $hostname],
        ['path' => 'system/full_page_cache/varnish/backend_host', 'value' => $hostname],
        ['path' => 'system/full_page_cache/varnish/backend_port', 'value' => 8080],
        ['path' => 'system/full_page_cache/varnish/grace_period', 'value' => 300],
    ];
    foreach ($configValue as $config) {
        $configWriter->save($config['path'], $config['value'], 'default', 0);
    }

}

function isXssInitialized() {
    $rootDirectory = getenv('PROJECT_ROOT');
    return file_exists($rootDirectory . "init.done");
}

function sendErrorEmail($errorMessage) {

    $MAIL_FROM = 'system-notice@x-shopping-st.com';
    $MAIL_TO   = 'dev-team@x-shopping-st.com';
    $hostname = getenv('HOST_NAME');
    $config = [
        'auth'     => 'login',
        'username' => getenv('SEND_GRID_API_ACCOUNT'),
        'password' => getenv('SEND_GRID_API_KEY'),
        'port'     => 587,
        'ssl' => 'tls'
    ];

    $transport = new Zend_Mail_Transport_Smtp('smtp.sendgrid.net', $config);
    $mail = new Zend_Mail();
    $mail->setBodyText($errorMessage);
    $mail->setFrom($MAIL_FROM, 'System Notice');
    $mail->addTo($MAIL_TO , 'Dev Team');
    $mail->setSubject("x-shopping-st $hostname init script failed");
    $mail->send($transport);

    echo 'Send system error mail'.PHP_EOL;
}

function executeCommand($command) {

    echo "Running $command command" . PHP_EOL;

    exec($command, $output, $resultCode);

    print_r( $output );

    if ( $resultCode != 0 ) {
        $errorContent = is_array($output) ? implode(PHP_EOL,$output) : $output;
        sendErrorEmail("\"$command\" command failed" ."\n" . "Command returns \"$errorContent\"");
        echo "Command failed" . "\n" . "$command" . PHP_EOL;
        exit(1);

    } else {

        echo "Executing \"$command\" success" . PHP_EOL;
    }
}
echo 'Finish executing init-script.php'.PHP_EOL;
