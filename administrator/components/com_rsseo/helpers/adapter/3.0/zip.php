<?php
/**
* @version 1.0.0
* @package RSJoomla! Adapter
* @copyright (C) 2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class RSZip 
{
	/**
	 * Beginning of central directory record.
	 *
	 * @var    string
	 * @since  11.1
	 */
	private $_ctrlDirHeader = "\x50\x4b\x01\x02";

	/**
	 * End of central directory record.
	 *
	 * @var    string
	 * @since  11.1
	 */
	private $_ctrlDirEnd = "\x50\x4b\x05\x06\x00\x00\x00\x00";

	/**
	 * Beginning of file contents.
	 *
	 * @var    string
	 * @since  11.1
	 */
	private $_fileHeader = "\x50\x4b\x03\x04";

	/**
	 * Create a ZIP compressed file from an array of file data.
	 *
	 * @param   string  $archive  Path to save archive.
	 * @param   array   $files    Array of files to add to archive.
	 * @param   array   $options  Compression options (unused).
	 *
	 * @return  boolean  True if successful.
	 *
	 * @since   11.1
	 *
	 * @todo    Finish Implementation
	 */
	public function create($archive, $files, array $options = array())
	{
		$contents = array();
		$ctrldir = array();

		foreach ($files as $file)
		{
			$this->_addToZIPFile($file, $contents, $ctrldir);
		}

		return $this->_createZIPFile($contents, $ctrldir, $archive);
	}
	
	/**
	 * Adds a "file" to the ZIP archive.
	 *
	 * @param   array  &$file      File data array to add
	 * @param   array  &$contents  An array of existing zipped files.
	 * @param   array  &$ctrldir   An array of central directory information.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 *
	 * @todo    Review and finish implementation
	 */
	private function _addToZIPFile(array &$file, array &$contents, array &$ctrldir)
	{
		$data = &$file['data'];
		$name = str_replace('\\', '/', $file['name']);

		/* See if time/date information has been provided. */
		$ftime = null;
		if (isset($file['time']))
		{
			$ftime = $file['time'];
		}

		// Get the hex time.
		$dtime = dechex($this->_unix2DosTime($ftime));
		$hexdtime = chr(hexdec($dtime[6] . $dtime[7])) . chr(hexdec($dtime[4] . $dtime[5])) . chr(hexdec($dtime[2] . $dtime[3]))
			. chr(hexdec($dtime[0] . $dtime[1]));

		/* Begin creating the ZIP data. */
		$fr = $this->_fileHeader;
		/* Version needed to extract. */
		$fr .= "\x14\x00";
		/* General purpose bit flag. */
		$fr .= "\x00\x00";
		/* Compression method. */
		$fr .= "\x08\x00";
		/* Last modification time/date. */
		$fr .= $hexdtime;

		/* "Local file header" segment. */
		$unc_len = strlen($data);
		$crc = crc32($data);
		$zdata = gzcompress($data);
		$zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$c_len = strlen($zdata);

		/* CRC 32 information. */
		$fr .= pack('V', $crc);
		/* Compressed filesize. */
		$fr .= pack('V', $c_len);
		/* Uncompressed filesize. */
		$fr .= pack('V', $unc_len);
		/* Length of filename. */
		$fr .= pack('v', strlen($name));
		/* Extra field length. */
		$fr .= pack('v', 0);
		/* File name. */
		$fr .= $name;

		/* "File data" segment. */
		$fr .= $zdata;

		/* Add this entry to array. */
		$old_offset = strlen(implode('', $contents));
		$contents[] = &$fr;

		/* Add to central directory record. */
		$cdrec = $this->_ctrlDirHeader;
		/* Version made by. */
		$cdrec .= "\x00\x00";
		/* Version needed to extract */
		$cdrec .= "\x14\x00";
		/* General purpose bit flag */
		$cdrec .= "\x00\x00";
		/* Compression method */
		$cdrec .= "\x08\x00";
		/* Last mod time/date. */
		$cdrec .= $hexdtime;
		/* CRC 32 information. */
		$cdrec .= pack('V', $crc);
		/* Compressed filesize. */
		$cdrec .= pack('V', $c_len);
		/* Uncompressed filesize. */
		$cdrec .= pack('V', $unc_len);
		/* Length of filename. */
		$cdrec .= pack('v', strlen($name));
		/* Extra field length. */
		$cdrec .= pack('v', 0);
		/* File comment length. */
		$cdrec .= pack('v', 0);
		/* Disk number start. */
		$cdrec .= pack('v', 0);
		/* Internal file attributes. */
		$cdrec .= pack('v', 0);
		/* External file attributes -'archive' bit set. */
		$cdrec .= pack('V', 32);
		/* Relative offset of local header. */
		$cdrec .= pack('V', $old_offset);
		/* File name. */
		$cdrec .= $name;
		/* Optional extra field, file comment goes here. */

		/* Save to central directory array. */
		$ctrldir[] = &$cdrec;
	}
	
	/**
	 * Creates the ZIP file.
	 *
	 * Official ZIP file format: http://www.pkware.com/appnote.txt
	 *
	 * @param   array   &$contents  An array of existing zipped files.
	 * @param   array   &$ctrlDir   An array of central directory information.
	 * @param   string  $path       The path to store the archive.
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   11.1
	 *
	 * @todo	Review and finish implementation
	 */
	private function _createZIPFile(array &$contents, array &$ctrlDir, $path)
	{
		$data = implode('', $contents);
		$dir = implode('', $ctrlDir);

		$buffer = $data . $dir . $this->_ctrlDirEnd . /* Total # of entries "on this disk". */
		pack('v', count($ctrlDir)) . /* Total # of entries overall. */
		pack('v', count($ctrlDir)) . /* Size of central directory. */
		pack('V', strlen($dir)) . /* Offset to start of central dir. */
		pack('V', strlen($data)) . /* ZIP file comment length. */
		"\x00\x00";

		if (JFile::write($path, $buffer) === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
		* Converts a UNIX timestamp to a 4-byte DOS date and time format
		* (date in high 2-bytes, time in low 2-bytes allowing magnitude
		* comparison).
		*
		* @param int $unixtime The current UNIX timestamp.
		*
		* @return int The current date in a 4-byte DOS format.
		*
		* @since 11.1
		*/
		protected function _unix2DOSTime($unixtime = null)
		{
			$timearray = (is_null($unixtime)) ? getdate() : getdate($unixtime);

			if ($timearray['year'] < 1980)
			{
				$timearray['year'] = 1980;
				$timearray['mon'] = 1;
				$timearray['mday'] = 1;
				$timearray['hours'] = 0;
				$timearray['minutes'] = 0;
				$timearray['seconds'] = 0;
			}

			return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) | ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
		}
}