<?php

class LevelGenerator
{
    public $level = array();

    private $generating;

    private $earlyDropChance = 2;
    
    private $maxRows    = 3;
    private $maxColumns = 3;
    
    private $currentRow    = 0;
    private $currentColumn = 0;

    private $direction;
    private $changeRow = false;
    private $lastDirection;


    /**
     * Loops to add the cells in the correct order
     * 
     * @return void
     */
    public function generateLevel()
    {
        $this->initLevel();
        $this->placeStart();

        $this->generating = true;
        while($this->generating) {

            $moved = $this->changeRow ? $this->changeRow() : $this->move();

            if($moved) {
                $this->placeCell();
            }
        }

        return $this->level;
    }



    /**
     * Initialises a matrix of filler cells
     * 
     * @return void
     */
    private function initLevel($blank = false)
    {
        // Fill the array with '0' squares
        for($r = 0; $r <= $this->maxRows; $r++) {
            for($c = 0; $c <= $this->maxColumns; $c++) {
                $this->level[$r][$c] = '0';
            }
        }
    }



    /**
     * Places the start block in a random place in the first row
     * 
     * @return void
     */
    private function placeStart()
    {
        $startPosition = mt_rand(0, $this->maxColumns);

        $this->currentRow    = 0;
        $this->currentColumn = $startPosition;

        $this->setCellContent('1-door');

        // Set the direction for the next cell to be placed in
        $this->setHorizontalDirection();
    }



    /**
     * Places all the other cells
     * 
     * @return void
     */
    private function placeCell()
    {
        $dropChance = $this->earlyDropChance == mt_rand(0, $this->earlyDropChance);

        // Just dropped down and doesn't want to drop again
        // Or Just dropped down to bottom row
        if($this->lastDirection == 'Down' && (!$dropChance || !$this->canMoveDown())) {
            $this->changeRow = false;
            $this->setCellContent(3);

        // Wanting to drop down
        } elseif(
            ($this->lastDirection == 'Left' && !$this->canMoveLeft()) ||
            ($this->lastDirection == 'Right' && !$this->canMoveRight()) ||
            $dropChance
        ) {

            $this->changeRow = true;
            
            // Going to move down
            if($this->canMoveDown()) {
                
                // If moved down last time we need to link to the top
                $this->setCellContent($this->lastDirection == 'Down' ? '2' : '2-a');

            // If it can't move down its on the bottom so drop a door
            } else {
                $this->setCellContent('1-door');
            }

        // Going horizontal so just stick in a normal
        }  else {
            $this->setCellContent('1');
        }
    }



    /**
     * Sets the horizontal direction based on the current position
     *
     * @return void
     */
    private function setHorizontalDirection()
    {
        if(!$this->canMoveRight()) {
            $this->direction = 'Left';
        } elseif(!$this->canMoveLeft()) {
            $this->direction = 'Right';
        } else {
            $this->direction = (mt_rand(0, 1)) ? 'Left' : 'Right';
        }
    }



    /**
     * Sets the content of a cell
     * 
     * @param string $content
     */
    private function setCellContent($content)
    {
        $this->level[$this->currentRow][$this->currentColumn] = $content;
    }


    /**
     * Moves the generator in any direction
     * 
     * @return boolean
     */
    private function move()
    {
        return $this->{'move'.$this->direction}();
    }



    /**
     * Moves the generator left
     * 
     * @return boolean
     */
    private function moveLeft()
    {
        if($this->canMoveLeft()) {
            $this->currentColumn--;
            $this->lastDirection = 'Left';
            return true;
        }
        return false;
    }



    /**
     * Moves the generator to the right
     * 
     * @return boolean
     */
    private function moveRight()
    {
        if($this->canMoveRight()) {
            $this->currentColumn++;
            $this->lastDirection = 'Right';
            return true;
        }
        return false;
    }



    /**
     * Swaps to the next row
     * 
     * @return boolean
     */
    private function changeRow()
    {
        if($this->canMoveDown()) {
            $this->currentRow++;
            $this->lastDirection = 'Down';
            $this->setHorizontalDirection();
            return true;
        }
        return $this->generating = false;
    }



    /**
     * Checks if the generator can move to the left
     * 
     * @return boolean
     */
    private function canMoveLeft()
    {
        return $this->currentColumn > 0;
    }



    /**
     * Checks if the generator can move to the right
     * 
     * @return boolean
     */
    private function canMoveRight()
    {
        return $this->currentColumn < $this->maxColumns;
    }



    /**
     * Checks if the generator can move down
     * 
     * @return boolean
     */
    private function canMoveDown()
    {
        return $this->currentRow < $this->maxRows;
    }
}