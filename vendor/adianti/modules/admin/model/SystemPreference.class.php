<?php
namespace Adianti\Modules\Admin\Model;

use Adianti\Database\TCriteria;
use Adianti\Database\TRecord;
use Adianti\Database\TRepository;

/**
 * SystemPreference
 *
 * @version    1.0
 * @package    model
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemPreference extends TRecord
{
    const TABLENAME  = 'sys_preference';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = null, $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('value');
    }
    
    /**
     * Retorna uma preferência
     * @param $id Id da preferência
     */
    public static function getPreference($id)
    {
        $preference = new SystemPreference($id);
        return $preference->value;
    }
    
    /**
     * Altera uma preferência
     * @param $id  Id da preferência
     * @param $value Valor da preferência
     */
    public static function setPreference($id, $value)
    {
        $preference = SystemPreference::find($id);
        if ($preference) {
            $preference->value = $value;
            $preference->store();
        }
    }

    /**
     * Retorna um array com todas preferências
     * @throws \Exception
     */
    public static function getAllPreferences()
    {
        $rep = new TRepository(SystemPreference::class);
        $objects = $rep->load(new TCriteria);
        $dataset = array();
        
        if ($objects) {
            foreach ($objects as $object) {
                $property = $object->id;
                $value    = $object->value;
                $dataset[$property] = $value;
            }
        }
        return $dataset;
    }
}
