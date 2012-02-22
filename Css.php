<?php


class Css {
    //this is an associative array that's used when replacing
	private $settings;
	//This is the path (relative to this file) to the dir that holds the css files to parse
	private $cssDir = "css/";
	//private varray to store the files
	private $cssFiles = array();
	//This is the path (relative to this file) to the dir where the parsed css files wil be written
	private $outCssDir = "../css/";

	/**
	 * the class thake only one parameters, an associative array of keys to replace with their respective values
	 */
	function __construct($settings) {
		$this -> settings = $settings;
		$this -> readFiles();
		$this -> replaceValuesInCssStrings();
		
	}

	private function readFiles() {
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this -> cssDir;
		if (file_exists($dir)) {
			if ($handle = opendir($dir)) {
				/* This is the correct way to loop over the directory. */
				while (false !== ($entry = readdir($handle))) {
					//filter out .. and .
					if ($entry !== "." && $entry !== "..") {
						$cssFiles[$entry] = file_get_contents($dir . $entry);
					}
				}

				closedir($handle);
			}
		}
		$this -> cssFiles = $cssFiles;

	}

	private function replaceValuesInCssStrings() {
		foreach ($this->cssFiles as $fileName => $fileContent) {
			$this->cssFiles[$fileName] = strtr($fileContent, $this-> settings);

		}
	}
	/**
	 * renders the parsed css files in a <style> tag
	 */
	public function render() {
		$css = "<style>\n";
		for ($i = 0; $i < count($this -> cssFiles); $i++) {
			$css .= $this -> cssFiles[$i];
		}
		$css .= "</style>";
		echo($css);
	}
	
	/**
	 * writes the parsed css files to the $outCssDir (keeping their names intact)
	 */
	public function renderToFile() {
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this -> outCssDir;
		if (file_exists($dir)) {
			foreach($this->cssFiles as $fileName => $fileContent){
				file_put_contents($dir . $fileName, $fileContent);

			}	
		}
	}
	/**
	 *  this is just in case you want to call echo ($css)
	 */
	public function __toString() {
		$this -> render();
	}

}
