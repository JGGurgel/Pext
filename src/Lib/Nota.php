<?php


namespace Jggurgel\Pext\Lib;

class Nota
{

    private const TOM = 2;
    private const SEMI_TOM = 1;
    private $note;
    function __construct($note)
    {
        $this->note = $note;
    }

    public function getNota()
    {
        return $this->note;
    }

    public function aumentarMeioTom(): self
    {
        return $this->aumentar(1);
    }
    public function aumentarTom()
    {
        return $this->aumentar(2);
    }

    function getEscalaMaior()
    {
        $regras = [self::TOM, self::TOM, self::SEMI_TOM, self::TOM, self::TOM, self::TOM, self::SEMI_TOM];
        return $this->getEscala($regras);
    }

    function getEscalaMenor()
    {
        $regras = [self::TOM, self::SEMI_TOM, self::TOM, self::TOM, self::SEMI_TOM, self::TOM, self::TOM];
        return $this->getEscala($regras);
    }

    function getEscala($regras)
    {
        $escala = [];
        $escala[] = $this->getNota();
        foreach ($regras as $regra) {
            $nota = $this->aumentar($regra)->getNota();
            $escala[] = $nota;
        }
        return $escala;
    }

    public static function getNotes(){

        return $notes = [
            "C",
            "C#",
            "D",
            "D#",
            "E",
            "F",
            "F#",
            "G",
            "G#",
            "A",
            "A#",
            "B",
        ];
    }

    private function aumentar($quantidade)
    {
        $notes = self::getNotes();
        $notesAlternate = [
            "C",
            "Db",
            "D",
            "Eb",
            "E",
            "F",
            "Gb",
            "G",
            "Ab",
            "A",
            "Bb",
            "B",
        ];

        $index = array_search($this->note, $notes, true);

        if($index === false){
            $index = array_search($this->note, $notesAlternate, true);
        }

        $newNoteIndex = $index + $quantidade;

        if($newNoteIndex > 11){
            $newNoteIndex = $newNoteIndex - count($notes);
        }

        $newNote = $notes[$newNoteIndex];

        if ($this->note[0] == $newNote[0]) {
          
            $newNote = $notesAlternate[$newNoteIndex];
        }
        $this->note = $newNote;
      
        return $this;
    }
}