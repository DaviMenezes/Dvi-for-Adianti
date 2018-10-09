<?php

namespace App\Service\Validator;

use App\Modules\Contact\Model\Entity;
use App\Modules\Contact\Model\Human;
use Dvi\Adianti\Database\Transaction;
use Dvi\Adianti\Helpers\Utils;
use Exception;

/**
 * Validator UniqueCpfValidatorService
 *
 * @package    Validator
 * @subpackage Service
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class UniqueCpfValidatorService
{
    public function validate($params)
    {
        try {
            $request = $params['request'];

            Transaction::open();
            $query = Human::where($params['property'], '=', $request['Human-cpf']);
            if (Utils::editing($request)) {
                $human = (new Entity($request['Entity-id']))->human();
                $query->where('id', '<>', $human->id);
            }
            $count = $query->count();
            Transaction::close();

            if ($count > 0) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            Transaction::rollback();
            throw $e;
        }
    }
}
