<?php namespace Simexis\Webthumb;

use Symfony\Component\Process\Process;
use Illuminate\Config\Repository;

class Webthumb
{
	const BIN_PATH_WINDOWS = 'bin\\win\\phantomjs.exe';
	const BIN_PATH_MAC = 'bin/mac/phantomjs';
	const BIN_PATH_LINUX_I686 = 'bin/linux/i686/phantomjs';
	const BIN_PATH_LINUX_X86_64 = 'bin/linux/x86_64/phantomjs';
	const SCRIPT_NAME = 'page.js';

	protected $bin = null;

	protected $url = null;

	protected $screen_width = 1280;
	protected $screen_height = 1024;

	protected $config;

	public function __construct(Repository $config)
    {
        $this->config = $config;

		// Switch the binary path for each OS
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { //Win
			$this->bin = static::BIN_PATH_WINDOWS;
		} elseif (strtoupper(PHP_OS) === 'DARWIN') { //OS X
			$this->bin = static::BIN_PATH_MAC;
		} elseif (strtoupper(PHP_OS) === 'LINUX') { //Linux
			if (preg_match('/x86_64/', php_uname())) { //x86_64
				$this->bin = static::BIN_PATH_LINUX_X86_64;
			} else { //i686
				$this->bin = static::BIN_PATH_LINUX_I686;
			}
		} else { //Unsupported
			throw new \OutOfBoundsException();
		}

		$configuration = $config->get('webthumb');
		$this->setScreenSize($configuration['bwidth'], $configuration['bheight']);
	}

	/**
	 * Set the URL of the capture target
	 * @param $url
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setUrl($url)
	{
		if ($url && filter_var($url, FILTER_VALIDATE_URL) === false) {
			throw new \InvalidArgumentException();
		}
		$this->url = $url;
		return $this;
	}

	/**
	 * Set the screen size
	 * @param int $width
	 * @param int $height
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setScreenSize($width = null, $height = null)
	{
		if (!is_null($width)) {
			$this->setScreenWidth($width);
		}
		if (!is_null($height)) {
			$this->setScreenHeight($height);
		}
		return $this;
	}

	/**
	 * Set the width of the screen size
	 * @param int $width
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setScreenWidth($width)
	{
		if (!is_numeric($width)) {
			throw new \InvalidArgumentException();
		}
		$this->screen_width = (int)$width;
		return $this;
	}

	/**
	 * Set the height of the screen size
	 * @param int $height
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setScreenHeight($height)
	{
		if (!is_numeric($height)) {
			throw new \InvalidArgumentException();
		}
		$this->screen_height = (int)$height;
		return $this;
	}

	/**
	 * Save the capture to the specified URL
	 * @param null|string $save_path
	 * @throws OutOfBoundsException
	 * @throws Error
	 * @return \stdClass
	 */
	public function save($save_path = null)
	{
		$config = $this->config->get('webthumb');

		//The installation if you do not specify the path to the appropriate directory
		if (is_null($save_path)) {
			$save_path = public_path() . DIRECTORY_SEPARATOR . $config['local_cache_dir'] . DIRECTORY_SEPARATOR . $config['local_cache_dir'] . DIRECTORY_SEPARATOR . 'webthumb_capture_' . uniqid(md5($this->url)) . '.' . $config['encoding'];
		}
		
		//and generates a command hit the phantomjs
		$command = implode(' ', [
			$config['phantom_js_root'] . DIRECTORY_SEPARATOR . $this->bin,
			$config['phantom_js_root'] . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . static::SCRIPT_NAME,
			sprintf('--url="%s"', $this->url),
			sprintf('--output="%s"', $save_path),
			'--width=' . $this->screen_width,
			'--height=' . $this->screen_height
		]);

		$proccess = new Process($command);
		if($proccess->run() === 0) {
			return json_decode($proccess->getOutput());
		}
		
		if($out = json_decode($proccess->getOutput())) {
			throw new Error($out->message, $out->code);
		}
		
		throw new Error('Undefined error!');
	}
}