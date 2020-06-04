<?php


namespace OnFact\Model;


class CustomerDocument extends Model
{

    /**
     * Returns the name. Either the default name or for a specific language
     *
     * @param null $languageId
     * @return mixed
     */
    public function getTotalAmountExcl($currencyId = '') {
        $totalAmountExcl = $this->_get('total_amount_excl');
        if (isset($totalAmountExcl[$currencyId])) {
            return $totalAmountExcl[$currencyId];
        } elseif (isset($name['_'])) {
            return $totalAmountExcl['_'];
        } else {
            return $totalAmountExcl;
        }
    }

}
