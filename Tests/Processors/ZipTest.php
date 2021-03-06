<?php

namespace Dizda\CloudBackupBundle\Tests\Processors;

use Dizda\CloudBackupBundle\Tests\AbstractTesting;

/**
 * Class ZipTest
 *
 * @package Dizda\CloudBackupBundle\Tests\Processors
 */
class ZipTest extends AbstractTesting
{
    /**
     * Test different commands
     */
    public function testGetCompressionCommand()
    {
        $processor = self::$kernel->getContainer()->get('dizda.cloudbackup.processor.zip');

        // build necessary data
        $rootPath = '/';
        $outputPath = '/var/backup/';
        $dateformat = 'Y-m-d_H-i-s';
        $processor->__construct($rootPath, $outputPath, 'database', array(), $dateformat, array());
        $archivePath = $outputPath . $processor->buildArchiveFilename();

        // compress with default params
        $processor->__construct($rootPath, $outputPath, 'database', array(), $dateformat, array());
        $this->assertEquals(
            $processor->getCompressionCommand($archivePath, $outputPath), 
            "cd $outputPath && zip -r $archivePath .");

        // compress with password
        $processor->__construct($rootPath, $outputPath, 'database', array(), $dateformat, array('password' => 'qwerty'));
        $this->assertEquals(
            $processor->getCompressionCommand($archivePath, $outputPath), 
            "cd $outputPath && zip -r -P qwerty $archivePath .");

        // compress with compression rate = 0
        $processor->__construct($rootPath, $outputPath, 'database', array(), $dateformat, array('compression_ratio' => 0));
        $this->assertEquals(
            $processor->getCompressionCommand($archivePath, $outputPath), 
            "cd $outputPath && zip -r -0 $archivePath .");

        // compress with compression rate = 9
        $processor->__construct($rootPath, $outputPath, 'database', array(), $dateformat, array('compression_ratio' => 9));
        $this->assertEquals(
            $processor->getCompressionCommand($archivePath, $outputPath), 
            "cd $outputPath && zip -r -9 $archivePath .");
    }

}
