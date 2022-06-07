<?php

declare(strict_types=1);
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model.
 *
 * @method \App\Model\Entity\User                                             newEmptyEntity()
 * @method \App\Model\Entity\User                                             newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[]                                           newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User                                             get($primaryKey, $options = [])
 * @method \App\Model\Entity\User                                             findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User                                             patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[]                                           patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false                                       save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User                                             saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface       saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface       deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method.
     *
     * @param array $config the configuration for the Table
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('user_id');
        $this->setPrimaryKey('user_id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator instance
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
        ;

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->sameAs('password_Conformation', 'password', 'Passwords Do Not Match')
            ->add('password', [
                'password' => [
                    'rule' => ['custom', '(^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]*).{8,}$)'],
                    'message' => 'Password should contain: 8 characters, 1 upper case, 1 digit',
                ]])
        ;

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules the rules object to be modified
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }
}
