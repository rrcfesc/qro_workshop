<?php


namespace AppBundle\Model;


class ChurroTypeStats
{
    private $type;
    private $avg;
    private $list;

    public function __construct($type, $avg, $list)
    {
        $this->type = $type;
        $this->avg = $avg;
        $this->list = $list;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getAvg()
    {
        return $this->avg;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

}