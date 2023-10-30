<?php

if(file_exists("init.done")){
    echo 'Magento has been installed !!!'.PHP_EOL;
    exit;
}

require 'app/autoload.php';
require_once('Zend/Mail/Transport/Smtp.php');
require_once 'Zend/Mail.php';

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$filesystem = $objectManager->create(\Magento\Framework\Filesystem::class);
$rootDirectory = $filesystem->getDirectoryWrite('base')->getAbsolutePath();

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

$connection = mysqli_connect($mysqlHost, $mysqlUser, $mysqlPass) ;
if (!$connection) {
    sendMailError('Cannot connect to database server', 'Connect database');
    echo 'Error : ' . mysqli_connect_error();
    exit;
}

$dbSelected = mysqli_select_db($connection,$mysqlDbName);

// check db exist
if (!$dbSelected) {
    echo 'Database "'. $mysqlDbName . '" not exist!'.PHP_EOL;
    exit;
}

//check table exist in database
if (mysqli_query($connection,"SELECT 1 FROM `core_config_data` LIMIT 0")) {
    echo 'Run setup:upgrade script'.PHP_EOL;

    exec("php {$rootDirectory}bin/magento setup:upgrade",$outputSetupUpgrade,$resultCodeSetupUpgrade);
    print_r($outputSetupUpgrade);

    if ($resultCodeSetupUpgrade) {
        sendMailError($outputSetupUpgrade, 'Setup:upgrade');
        echo 'Run setup:upgrade command fail'.PHP_EOL;
        exit;
    } else {
        echo 'Run setup:upgrade command success'.PHP_EOL;
    }

    echo 'Run setup:static-content:deploy script'.PHP_EOL;

    exec("php {$rootDirectory}bin/magento setup:static-content:deploy -f",$outputStaticContent,$resultCodeStaticContent);
    print_r($outputStaticContent);
    if ($resultCodeStaticContent) {
        sendMailError($outputStaticContent, 'Setup:static-content:deploy');
        echo 'Run setup:static-content:deploy command fail'.PHP_EOL;
        exit;
    } else {
        echo 'Run setup:static-content:deploy command success'.PHP_EOL;
    }
    exit;
} else {
    echo 'Starting install magento'.PHP_EOL;

    $etcPathFolder = $filesystem->getDirectoryWrite('etc')->getAbsolutePath();
    unlink($etcPathFolder.'env.php');

    echo 'remove env.php'.PHP_EOL;

    exec("rm -rf generated/code/ && rm -rf pub/static/deployed_version.txt && rm -rf var/cache/ var/page_cache/ var/view_preprocessed/",$outputClear);

    echo 'remove cache/ generated/ pub/static folder'.PHP_EOL;
    $commandInstall = "php -f {$rootDirectory}bin/magento setup:install --base-url=http://{$hostname} --base-url-secure=https://{$hostname} --db-host={$mysqlHost} --db-name={$mysqlDbName}  --db-user={$mysqlUser} --db-password={$mysqlPass} --admin-firstname={$adminFirstName} --admin-lastname={$adminLastName} --admin-email={$adminEmail} --admin-user={$adminUser} --admin-password={$adminPass} --language=ja_JP --currency=JPY --timezone=Asia/Tokyo --backend-frontname={$adminPageSlug} --use-secure=1 --use-secure-admin=1";
    echo 'Run setup:install script'.PHP_EOL;

    exec($commandInstall,$outputInstall,$resultCodeInstall);
    print_r($outputInstall);

    if ($resultCodeInstall) {
        sendMailError($outputInstall, 'Setup:install');
        print_r($outputInstall);
        echo 'Run setup:install command fail'.PHP_EOL;
        exit;
    } else {
        echo 'Run setup:install command success'.PHP_EOL;
    }
    copy($etcPathFolder.'env.php.tpl',$etcPathFolder.'env.php');
    echo 'Install magento successful'.PHP_EOL;
    fopen("init.done", "w");
    exit;
}

function sendMailError($errorContent, $stepError) {
    $errorContent = is_array($errorContent) ? implode(PHP_EOL,$errorContent) : $errorContent;

    $config = [
        'auth'     => 'login',
        'username' => getenv('SEND_GRID_API_ACCOUNT'),
        'password' => getenv('SEND_GRID_API_KEY'),
        'port'     => 587,
        'ssl' => 'tls'
    ];

    $transport = new Zend_Mail_Transport_Smtp('smtp.sendgrid.net', $config);
    $mail = new Zend_Mail();
    $mail->setBodyText($errorContent);
    $mail->setFrom('dev-team@true-inc.jp', 'Dev Team');
    $mail->addTo('dev-team@true-inc.jp', 'Dev Team');
    $mail->setSubject($stepError. ' error when deploy magento');
    $mail->send($transport);

    echo 'Send email to dev-team success!'.PHP_EOL;
}
?>
