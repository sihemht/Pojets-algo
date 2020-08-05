<?php
namespace inc;


class Algorithm
{
	
	 // Declaration des propriétés
	 
	private $handle;
	private $content;
	private $strTotLines;
	private $intTotLines;
	private $accBuffer;
	private $line;
	private $tmpMap;
	private $count;
	private $maxSq;
	private $countY;
	private $coordY;
	private $totCoordY;
	private $errors;


	public function __construct()
	{
		global $argv;

		$this->handle      = @fopen($argv[1], 'r');
		$this->content     = @file_get_contents($argv[1]);
		$this->strTotLines = @fgets($this->handle);
		$this->strTotLines = @trim($this->strTotLines);
		$this->intTotLines = @intval($this->strTotLines);
		$this->accBuffer   = '';
		$this->line        = 1;
		$this->tmpMap      = [];
		$this->count       = 0;
		$this->maxSq       = 1;
		$this->countY      = 1;
		$this->coordY      = strval($this->countY);
		$this->totCoordY   = strval($this->intTotLines);
	}


	public function convertToInt($line)
	{
		for ($i = 0; $i < strlen($line); $i++) {
			$char = substr($line, $i, 1);
			if ($char == 'o') {
				$char = 0;
			} elseif ($char == '.') {
				$char = 1;
			}

			$this->tmpMap[$this->count][$i] = $char;
		}
	}

	
	 
	// trouve les regtangles dans la map
	 
	public function findAllSquares()
	{
		for ($y = 1; $y < count($this->tmpMap); $y++) {
			for($x = 1; $x < count($this->tmpMap[0]); $x++) {
				if ($this->tmpMap[$y][$x] == 1) {
					$this->tmpMap[$y][$x] =
						min(
							$this->tmpMap[$y - 1][$x],
							$this->tmpMap[$y][$x - 1],
							$this->tmpMap[$y - 1][$x - 1]
						) + 1;
					if ($this->tmpMap[$y][$x] > $this->maxSq) {
						$this->maxSq = $this->tmpMap[$y][$x];
					}
				}
			}
		}
	}

	/**
	 * Read the content of the map line by line
	 *
	 * @return boolean FALSE if an error is detected
	 */
	public function readByLine()
	{
		while (($buffer = fgets($this->handle, 4096)) !== FALSE) {
			$this->line      += 1;
			$nbCharLine       = strlen($buffer) - 1;
			$totChar[]        = strlen($buffer) - 1;
			$this->accBuffer .= $buffer;
			$buffer = preg_replace('#\n#', '', $buffer);
			if ($this->convertToInt($buffer) === FALSE) {
				return FALSE;
			}

			$this->convertToInt($buffer);
			$this->count++;
			$this->findAllSquares();
		}
	}

	/**
	 * Draw the first biggest square found in the map
	 */
	public function drawSquare()
	{
		for ($y = 0; $y < count($this->tmpMap); $y++) {
			for($x = 0; $x < count($this->tmpMap[0]); $x++) {
				if ($this->tmpMap[$y][$x] == $this->maxSq) {
					$this->tmpMap[$y][$x] = 'X';

					for ($i = 0; $i <= $this->maxSq - 1; $i++) {
						for ($j = 0; $j <= $this->maxSq - 1; $j++) {
							$this->tmpMap[$y - $i][$x - $j] = 'X';
						}
					}

					break 2;
				}else{
					$this->tmpMap[$y][$x] = "1";
				}
			}
		}
	}

	/**
	 * Add spaces for the alignment of the ordinate column
	 *
	 * @param  string $solvedMap Number of the row
	 *
	 * @return string $row       Number of the row
	 */
	public function addSpaces($row = NULL)
	{
		while (strlen($this->coordY) < strlen($this->totCoordY)) {
			$this->coordY = substr_replace($this->coordY, ' ', 0, 0);
		}

		if ($row == NULL) {
			$row = $this->coordY;
		} else {
			$row .= "\n$this->coordY";
		}

		return $row;
		// var_dump($row);
	}

	/**
	 * Fill the solved map in a string with the result stored in an array,
	 * reconvert the characters of the map and format it
	 *
	 * @param  string $solvedMap Beginning of the solved map
	 *
	 * @return string $solvedMap Complete solved map
	 */
	public function fillSolvedMap($solvedMap)
	{
		foreach ($this->tmpMap as $key => $l) {
			foreach ($l as $c) {
				if ($c != 'X' && $c != 0) {
					$c = '.';
				} elseif ($c === 0) {
					$c = 'o';
				}

				$solvedMap .= $c;
			}

			$this->countY++;
			$this->coordY = strval($this->countY);

			$solvedMap = $this->addSpaces($solvedMap);
		}

		$solvedMap = preg_replace('#[X]#', "X",
			$solvedMap);
		$solvedMap = preg_replace('#[.]#', ".", $solvedMap);
		$solvedMap = preg_replace('#[o]#', ".", $solvedMap);
		$solvedMap = preg_replace('#[\n0-9]+$#', '', $solvedMap);

		return $solvedMap;
	}

	/**
	 * Write in the terminal the final result with details
	 *
	 * @param string $solvedMap Solved map with the first biggest square
	 */
	public function writeTerminal($solvedMap)
	{

		echo "\n\n" . $this->content . "\n\n";
		echo "\n\n" . $solvedMap . "\n\n\n";
		}

	//fonctions final qui appel les autres

	public function renderResolvedMap()
	{

		if ($this->readByLine() === FALSE) {
			return;
		}

		$this->readByLine();


		fclose($this->handle);

		$this->drawSquare();

		$solvedMap = $this->addSpaces();
		$solvedMap = $this->fillSolvedMap($solvedMap);

		$this->writeTerminal($solvedMap);
	}
}

$algorithm = new Algorithm;
$algorithm->renderResolvedMap();