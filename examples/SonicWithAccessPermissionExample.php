<?php namespace sgoendoer\Sonic\examples;

require_once(__DIR__ . '/../vendor/autoload.php');

use sgoendoer\Sonic\Sonic;
use sgoendoer\Sonic\Identity\EntityAuthData;
use sgoendoer\Sonic\Identity\SocialRecordManager;

try
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// setting Configuration
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// setting all configuration values to defaults
	Configuration::setVerbose(0);
	Configuration::setCurlVerbose(0);
	Configuration::setLogfile('sonic.log');
	Configuration::setApiPath('/sonic/');
	Configuration::setTimezone('Europe/Berlin');
	Configuration::setPrimaryGSLSNode('130.149.22.135:4002');
	Configuration::setSecondaryGSLSNode('130.149.22.133:4002');
	Configuration::setRequestTimeout(10);
	Configuration::setGSLSTimeout(4);
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// importing SocialRecord objects to work with
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// load SocialRecords from files to instatiaze the Sonic framework
	$srp = SocialRecordManager::importSocialRecord(file_get_contents(__DIR__ . '/data/SRPlatform.json'));
	$platformSocialRecord = $srp['socialRecord'];
	$platformAccountKeyPair = $srp['accountKeyPair'];
	$platformPersonalKeyPair = $srp['personalKeyPair'];
	
	$sra = SocialRecordManager::importSocialRecord(file_get_contents(__DIR__ . '/data/SRAlice.json'));
	$userSocialRecord = $sra['socialRecord'];
	$userAccountKeyPair = $sra['accountKeyPair'];
	$userPersonalKeyPair = $sra['personalKeyPair'];
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// initializing Sonic SDK
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// instantiaze the Sonic framework with the platform's SocialRecord
	$sonic = Sonic::initInstance(new EntityAuthData($platformSocialRecord, $platformAccountKeyPair, $platformPersonalKeyPair));
	Sonic::setUserAuthData(new EntityAuthData($userSocialRecord, $userAccountKeyPair));
	Sonic::setContext(Sonic::CONTEXT_USER);
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// setting up managers
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// setting a AccessPermissionManager
	$sonic->setAccessControlManager(new AccessControlManagerExample(AccessControlManager::DIRECTIVE_ALLOW, AccessControlManager::DIRECTIVE_DENY));
	
	// setting up SocialRecordCaching
	$sonic->setSocialRecordCaching(new SocialRecordCacheExample());
	
	// setting up UniqueIDManagement
	$sonic->setUniqueIDManager(new UniqueIDManagerExample());
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// sending out requests
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$globalIDBob = '28B6TE8T9NUO202C5NZIUTNQSP88E70B8JAWH4FQ58OJOB8LIF';
	
	// doing a GET request
	PersonAPIExample::performGETPersonRequest($globalIDBob);
}
catch(\Exception $e)
{
	die($e->getMessage() . "\n\n" . $e->getTraceAsString());
}

?>