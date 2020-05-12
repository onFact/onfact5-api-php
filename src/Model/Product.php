<?php


namespace OnFact\Model;


class Product extends Model
{

    /**
     * Returns the name. Either the default name or for a specific language
     *
     * @param null $languageId
     * @return mixed
     */
    public function getName($languageId = '') {
        $name = $this->_get('name');
        if (isset($name[$languageId])) {
            return $name[$languageId];
        } elseif (isset($name['_'])) {
            return $name['_'];
        } else {
            return $name;
        }
    }

    /**
     * Returns the price. Either the default price of for a specific contact tier.
     *
     * @param null $contactTierId
     * @return mixed
     */
    public function getPrice($contactTierId = null) {
        $price = $this->_get('price');
        if (isset($price[$contactTierId])) {
            return $price[$contactTierId];
        } elseif (isset($price['_'])) {
            return $price['_'];
        } else {
            return $price;
        }
    }

}
