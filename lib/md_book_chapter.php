<?php
class MdBookChapter {

	/**
	 * @var MdBook
	 */
	var $book = null;

	/**
	 * Filename.
	 *
	 * @var string
	 */
	var $chapter_file = null;

	/**
	 * Chapter ordering number in a section.
	 */
	var $chapter_no = null;

	/**
	 * Title of this chapter.
	 *
	 * This string is taken from the first line of files content.
	 *
	 * @var string
	 */
	var $title = null;

	/**
	 * Name part of this chapters file name.
	 *
	 * @var string
	 */
	var $name = null;

	/**
	 * Previous chapter in current section.
	 *
	 * @var MdBookChapter
	 */
	var $prev_chapter = null;

	/**
	 * Next chapter in current section.
	 *
	 * @var MdBookChapter
	 */
	var $next_chapter = null;

	/**
	 * Link to parent chapter.
	 *
	 * @var MdBookChapter
	 */
	var $parent_chapter = null;

	var $subchapters = array();

	function __construct($book, $chapter_file, &$parent_chapter=null) {
		$this->chapter_file = $chapter_file;
		$this->parent_chapter = $parent_chapter;
		$this->book = $book;
		$this->_readContent();
		$this->_sortContent();
		$this->_linkChapters();
	}

	/**
	 * Gets identifier of chapter.
	 *
	 * The identifier has form that could be used by {@link getChapter()} method.
	 *
	 * @return string
	 */
	function getId() {
		$parent_id = "";
		if ($this->parent_chapter) {
			$parent_id = $this->parent_chapter->getId().":";
		}
		return "$parent_id".$this->getName();
	}

	/**
	 * Gets name of file with the chapter content.
	 *
	 * @return string
	 */
	function getFile() {
		return $this->chapter_file;
	}

	/**
	 * Gets name of chapter that is taken from filename.
	 *
	 * @return string
	 */
	function getName() {
		return $this->name;
	}

	/**
	 * Gets title of chapter.
	 *
	 * First nonempty line of content is used.
	 *
	 * @return string
	 */
	function getTitle() {
		return $this->title;
	}

	/**
	 * Gets next chapter.
	 *
	 * @return MdBookChapter
	 */
	function getNextChapter() {
		return $this->next_chapter;
	}

	/**
	 * Gets previous chapter.
	 *
	 * @return MdBookChapter
	 */
	function getPrevChapter() {
		return $this->prev_chapter;
	}

	/**
	 * Gets chapter number.
	 *
	 * Numbers from beginning of filename are used. Subchapters are divided by dot
	 *
	 * @return integer
	 */
	function getNo() {
		$parent_no = "";
		if ($this->parent_chapter) {
			$parent_no = $this->parent_chapter->getNo().".";
		}
		return "$parent_no$this->chapter_no";
	}

	function getParentChapter() {
		return $this->parent_chapter;
	}

	/**
	 * Gets HTML formatted content of chapter.
	 *
	 * @return string
	 */
	function getContent() {
		$raw = Files::GetFileContent($this->getFile());
		return $this->book->renderContent($raw);
	}

	/**
	 * Detects if chapter has some subchapters.
	 *
	 * @return boolean
	 */
	function hasSubchapters() {
		return sizeof($this->subchapters)>0;
	}

	/**
	 * Gets subchapters
	 *
	 * @return array
	 */
	function getSubchapters() {
		return $this->subchapters;
	}

	/**
	 * Gets a chapter.
	 *
	 * Chapter or path to a chapter can be specified as a string or as an array.
	 * When string is used path elements are divided by semicolon
	 *
	 * @param string|array $chapter_ids
	 * @return MdBookChapter
	 */
	function getChapter($chapter_ids) {
		if (is_string($chapter_ids)) {
			$chapter_ids = preg_split("/:/", $chapter_ids);
		}
		$chapter = array_shift($chapter_ids);
		foreach($this->subchapters as &$_c) {
			if ($_c->getName()==$chapter)
				if ($chapter_ids) {
					return $_c->getChapter($chapter_ids);
				} else {
					return $_c;
				}
		}
		return null;
	}

	private function _readContent() {
		if (is_dir($this->chapter_file)) {
			foreach(scandir($this->chapter_file) as $entry) {
				# vyradime nevhodne soubory
				if ($entry=="." || $entry=="..")
					continue;
				# vyradime soubory, ktere nevyhovuji schematu
				if (!preg_match("/^(\d+)-(.+)$/",$entry))
					continue;
				# soubor index.md nezpracujeme jako podrazenou kapitolu; ten pouzijeme pro aktualni slozku
				if ($entry=="index.md") {
					continue;
				}
				# scan section directory
				$chapter = new MdBookChapter($this->book,$this->chapter_file."/$entry", $this);
				$this->subchapters[] = $chapter;
			}

			# u adresare si vezmeme udaje o kapitole z nazvu adresare
			if (preg_match('/.+\/(\d+)-(.+?)$/', $this->chapter_file, $matches)) {
				$this->chapter_no = (int)$matches[1];
				$this->name = $matches[2];
			}
			$this->chapter_file .= "/index.md";
		} else {
			if (preg_match('/.+\/(\d+)-(.+?)\.(md|markdown)$/', $this->chapter_file, $matches)) {
				$this->chapter_no = (int)$matches[1];
				$this->name = $matches[2];
			}
		}
		if (file_exists($this->chapter_file)) {
			$content = preg_split("/\n/", Files::GetFileContent($this->chapter_file),-1,PREG_SPLIT_NO_EMPTY);
		} else {
			$content = array(basename(dirname($this->chapter_file)));
		}

		$this->title = $content[0];
		return true;
	}

	private function _sortContent() {
		if (sizeof($this->subchapters)>1) {
			usort($this->subchapters, array("MdBookChapter","CmpChapters"));
		}
		$prev_ch = null;
		foreach($this->subchapters as $_ch) {
			if (isset($prev_ch)) {
				$_ch->prev_chapter = $prev_ch;
				$prev_ch->next_chapter = $_ch;
			}
			$prev_ch = $_ch;
		}

		return true;
	}

	/**
	 * Just link all chapters with subchapters.
	 */
	private function _linkChapters() {
		$prev_ch = null;
		foreach($this->subchapters as &$ch) {
			# vazba mezi indexem a prvni podkapitolou
			if ($ch->hasSubchapters()) {
				$ch->next_chapter = $ch->subchapters[0];
				$ch->subchapters[0]->prev_chapter = $ch;
			}
			# vazba mezi indexem a posledni podkapitolou predchozi kapitoly
			if ($prev_ch) {
				if ($prev_ch->hasSubchapters()) {
					$ch->prev_chapter = $prev_ch->subchapters[sizeof($prev_ch->subchapters)-1];
					$prev_ch->subchapters[sizeof($prev_ch->subchapters)-1]->next_chapter = $ch;
				}
			}
			$prev_ch = $ch;
		}
	}

	static function CmpChapters($ch1,$ch2) {
		$a = $ch1->chapter_no;
		$b = $ch2->chapter_no;
		if ($a==$b)
			return 0;
		return ($a<$b)?-1:1;
	}
}
