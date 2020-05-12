<?php


namespace OnFact\Helper;


use OnFact\Model\ModelFactory;

class Index
{

    private $count;
    private $paging;
    private $items = [];

    /**
     * Index constructor.
     */
    public function __construct(string $model, $data = [])
    {
        $this->count = $data['count'] ?? null;
        $this->paging = isset($data['paging']) ? new Paging($data['paging']) : null;

        if (isset($data['items'])) {
            foreach ($data['items'] as $i => $item) {
                $this->items[] = ModelFactory::create($model, $item);
            }
        }
    }


    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getPaging()
    {
        return $this->paging;
    }

    /**
     * @param mixed $paging
     */
    public function setPaging($paging)
    {
        $this->paging = $paging;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

}
