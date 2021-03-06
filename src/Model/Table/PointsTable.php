<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Points Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Point get($primaryKey, $options = [])
 * @method \App\Model\Entity\Point newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Point[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Point|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Point|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Point patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Point[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Point findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PointsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('points');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);

        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id'
        ]);
    }


    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }


    public function getTotal($store_id=null, $month=null){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT SUM(points.point) as total FROM points
            WHERE points.month = $month AND points.store_id = $store_id
            GROUP BY store_id"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }
        return $results;
    }

    /**
     * course_progress dos usuarios de uma determinada loja
     */
    public function progressByStore($store_id=null){
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT cp.id as cp_id, cp.course_id, users.id as user_id, users.name as user_name, users.store_id as store_id from course_progress as cp
            left join users on cp.user_id = users.id
            where cp.course_id = 4 and store_id = $store_id"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }
        return $results;
    }

    /** active users before determined month */
    public function listActiveUsersBefore($store_id=null,$month=null){
        $month = 12;
        $connection = ConnectionManager::get('default');
        $results = $connection->execute(
            "SELECT * from users where store_id = $store_id and active=1 and created < '2018-$month-01 00:00:00'"
            )->fetchAll('assoc');

            if(empty($results)){
                $results = [];
            }
        return $results;
    }
}
