<?php
/*"******************************************************************************************************
*   (c) 2004-2006 by MulchProductions, www.mulchprod.de                                                 *
*   (c) 2007-2008 by Kajona, www.kajona.de                                                              *
*       Published under the GNU LGPL v2.1, see /system/licence_lgpl.txt                                 *
*-------------------------------------------------------------------------------------------------------*
* 																										*
* 	interface_db_driver.php                                                                             *
* 	Interface for all admin-classes																		*
*																										*
*-------------------------------------------------------------------------------------------------------*
*	$Id$                                      *
********************************************************************************************************/

/**
 * Interface to specify the layout of db-drivers.
 * Implement this interface, if you want to provide a db-layer for Kajona
 *
 * @package modul_system
 */
interface interface_db_driver {

    /**
     * This method makes sure to connect to the database properly
     *
     * @param string $strHost
     * @param string $strUsername
     * @param string $strPass
     * @param string $strDbName
     * @param int $intPort
     * @return bool
     */
    public function dbconnect($strHost, $strUsername, $strPass, $strDbName, $intPort);
    
    /**
     * Closes the connection to the database 
     */
    public function dbclose();

    /**
     * Sends a query (e.g. an update) to the database
     *
     * @param string $strQuery
     * @return bool
     */
    public function _query($strQuery);

    /**
     * This method is used to retrieve an array of resultsets from the database
     *
     * @param string $strQuery
     * @return array
     */
    public function getArray($strQuery);

    /**
     * Returns just a part of a recodset, defined by the start- and the end-rows,
     * defined by the params
     * <b>Note:</b> Use array-like counters, so the first row is startRow 0 whereas 
     * the n-th row is the (n-1)th key!!!
     *
     * @param string $strQuery
     * @param int $intStart
     * @param int $intEnd
     * @return array
     */
    public function getArraySection($strQuery, $intStart, $intEnd);

    /**
     * Returns the last error reported by the database.
     * Is being called after unsuccessful queries
     *
     * @return string
     */
    public function getError();

    /**
     * Returns ALL tables in the database currently connected to.
     * The method should return an array using the following keys:
     * name => Table name
     *
     * @return array
     */
    public function getTables();
    
    /**
     * Looks up the columns of the given table.
     * Should return an array for each row consting of:
     * array ("columnName", "columnType")
     *
     * @param string $strTableName
     * @return array
     */
    public function getColumnsOfTable($strTableName);

    /**
     * Used to send a create table statement to the database
     * By passing the query through this method, the driver can
     * add db-specific commands.
     * The array of fields should have the following structure
     * $array[string columnName] = array(string datatype, boolean isNull [, default (only if not null)])
     * whereas datatype is one of the following:
     * 		int
     * 		double
     * 		char10
     * 		char20
     * 		char100
     * 		char254
     * 		text 
     *
     * @param string $strName
     * @param array $arrFields array of fields / columns
     * @param array $arrKeys array of primary keys
     * @param array $arrIndices array of additional indices
     * @param bool $bitTxSafe Should the table support transactions?
     * @return bool
     */
    public function createTable($strName, $arrFields, $arrKeys, $arrIndices = array(), $bitTxSafe = true);


    /**
     * Starts a transaction
     *
     */
    public function transactionBegin();

    /**
     * Ends a successfull operation by Commiting the transaction
     *
     */
    public function transactionCommit();

    /**
     * Ends a non-successfull transaction by using a rollback
     *
     */
    public function transactionRollback();

    /**
     * returns an array with infos about the current database
     * The array returned should have tho following structure:
     * ["dbserver"]
     * ["dbclient"]
     * ["dbconnection"]
     *
     * @return mixed
     */
    public function getDbInfo();

    /**
     * Creates an db-dump usind the given filename. the filename is relative to _realpath_
     * The dump must include, and ONLY include the pass tables
     *
     * @param string $strPath
     * @param array $arrTables
     * @return bool Indicates, if the dump worked or not
     *
     */
    public function dbExport($strFilename, $arrTables);

    /**
     * Imports the given db-dump file to the database. The filename ist relativ to _realpath_
     *
     * @param string $strFilename
     * @return bool
     */
    public function dbImport($strFilename);
    
    /**
     * Allows the db-driver to add database-specific surrounding to column-names.
     * E.g. needed by the mysql-drivers
     *
     * @param string $strColum
     * @return string
     */
    public function encloseColumnName($strColumn);
}


?>
