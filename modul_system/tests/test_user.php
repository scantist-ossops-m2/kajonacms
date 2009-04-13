<?php

// includes
include_once(_adminpath_."/class_modul_user_admin.php");
include_once(_systempath_."/class_modul_user_user.php");
include_once(_systempath_."/class_modul_user_group.php");

class class_test_user implements interface_testable {



    public function test() {
        $objDB = class_carrier::getInstance()->getObjDB();

        echo "\tmodul_user...\n";

        //blank system - one user should have been created

        echo "\tcheck number of users installed...\n";
        $arrUserInstalled = class_modul_user_user::getAllUsers();
        class_assertions::assertEqual(count($arrUserInstalled), 1, __FILE__." checkNrOfUsers");



        echo "\tcheck number of groups installed...\n";
        $arrGroupsInstalled = class_modul_user_group::getAllGroups();
        class_assertions::assertEqual(count($arrGroupsInstalled), 2, __FILE__." checkNrOfGroups");



        echo "\tcreate 100 users using the model...\n";
        $arrUsersCreated = array();
        for($intI =0; $intI < 100; $intI++) {
            $objUser = new class_modul_user_user();
            $objUser->setStrEmail(generateSystemid()."@".generateSystemid()."de");
            $strUsername = "user_".generateSystemid();
            $objUser->setStrUsername($strUsername);
            $objUser->saveObjectToDb();
            $arrUsersCreated[] = $objUser->getSystemid();
            $strID = $objUser->getSystemid();
            $objDB->flushQueryCache();
            $objUser = new class_modul_user_user($strID);
            class_assertions::assertEqual($objUser->getStrUsername(), $strUsername, __FILE__." checkNameOfUserCreated");
        }
        $arrUserInstalled = class_modul_user_user::getAllUsers();
        class_assertions::assertEqual(count($arrUserInstalled), 101, __FILE__." checkNrOfUsersCreatedByModel");



        echo "\tcreate 100 groups using the model...\n";
        $arrGroupsCreated = array();
        for($intI =0; $intI < 100; $intI++) {
            $objGroup = new class_modul_user_group();
            $strName = "name_".generateSystemid();
            $objGroup->setStrName($strName);
            $objGroup->saveObjectToDb();
            $strID = $objGroup->getSystemid();
            $arrGroupsCreated[] = $objGroup->getSystemid();
            $objDB->flushQueryCache();
            $objGroup = new class_modul_user_group($strID);
            class_assertions::assertEqual($objGroup->getStrName(), $strName, __FILE__." checkNameOfGroupCreated");
        }
        $arrGroupsInstalled = class_modul_user_group::getAllGroups();
        class_assertions::assertEqual(count($arrGroupsInstalled), 102, __FILE__." checkNrOfGroupsByModel");



        echo "\tdeleting users created...\n";
        foreach($arrUsersCreated as $strOneUser) {
                $objUser = new class_modul_user_user($strOneUser);
            $objUser->deleteUser();
            $objDB->flushQueryCache();
        }


        echo "\tcheck number of users installed...\n";
        $arrUserInstalled = class_modul_user_user::getAllUsers();
        class_assertions::assertEqual(count($arrUserInstalled), 1, __FILE__." checkNrOfUsers");



        echo "\tdeleting groups created...\n";
        foreach($arrGroupsCreated as $strOneGroup) {
            class_modul_user_group::deleteGroup($strOneGroup);
            $objDB->flushQueryCache();
        }

        echo "\tcheck number of groups installed...\n";
        $arrGroupsInstalled = class_modul_user_group::getAllGroups();
        class_assertions::assertEqual(count($arrGroupsInstalled), 2, __FILE__." checkNrOfGroups");

        echo "\ttest group membership handling...\n";
        $objGroup = new class_modul_user_group();
        $objGroup->setStrName("AUTOTESTGROUP");
        $objGroup->saveObjectToDb();

        echo "\tadding 100 members to group...\n";
        for ($intI = 0; $intI <= 100; $intI++) {
            $objUser = new class_modul_user_user();
            $objUser->setStrUsername("AUTOTESTUSER_".$intI);
            $objUser->setStrEmail("autotest_".$intI."@kajona.de");
            $objUser->saveObjectToDb();
            //add user to group
            class_modul_user_group::addUserToGroups($objUser, array($objGroup->getSystemid()));
            $arrUsersInGroup = class_modul_user_group::getGroupMembers($objGroup->getSystemid());
            class_assertions::assertTrue($objGroup->isUserMemberInGroup($objUser), __FILE__." checkUserInGroup");
            class_assertions::assertEqual(count($arrUsersInGroup), 1+$intI, __FILE__." checkNrOfUsersInGroup");
            $objDB->flushQueryCache();
        }

        echo "\tdeleting groups & users\n";
        foreach(class_modul_user_group::getGroupMembers($objGroup->getSystemid()) as $objOneUser)
            $objOneUser->deleteUser();
        class_modul_user_group::deleteGroup($objGroup->getSystemid());


    }

}

?>