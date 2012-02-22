<?php

/**
 *
 */
class Css {

	private $settings;
	private $cssDir = "css/";
	private $cssFiles = array();
	private $outCssDir = "../colors/";

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

	public function render() {
		$css = "<style>\n";
		for ($i = 0; $i < count($this -> cssFiles); $i++) {
			$css .= $this -> cssFiles[$i];
		}
		$css .= "</style>";
		echo($css);
	}
	
	public function renderToFile() {
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this -> outCssDir;
		if (file_exists($dir)) {
			foreach($this->cssFiles as $fileName => $fileContent){
				file_put_contents($dir . $fileName, $fileContent);

			}	
		}
	}

	public function __toString() {
		$this -> render();
	}

}
