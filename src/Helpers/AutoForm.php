<?php

namespace App\Modules\Backend\Helpers {

    use Core\Forms\Decorator\BootstrapHorizontalFormDecorator as Form;

    /**
     * Class AutoForm
     *
     * @package App\Modules\Backend\Helpers
     */
    class AutoForm extends Form
    {

        public $fields = [];
        private $classPrefix = '\\Phalcon\\Forms\\Element\\';

        /**
         * @param $model
         * @param $param
         * @SuppressWarnings(PHPMD.UnusedFormalParameter)
         */
        public function initialize($model, $param = false)
        {

            // указываем параметр action
            $this->setAction($param['formAction']);

            //Заполняем поля формы данными из модели
            $this->setEntity($model);

            // Получаем аннотации из модели
            $metadata = $this->annotations->get($model);

            // Получаем все аннотации аттрибутов класса-модели
            $annotations = $metadata->getPropertiesAnnotations();

            // Считыаем аннотации с @FormField
            foreach ($annotations as $attribute => $annotation) {

                if ($annotations[$attribute]->has('FormField')) {

                    $this->fields[$attribute] = $annotations[$attribute]->get('FormField')->getArguments();
                }
            }

            // Создаем поля формы с учетом видимости
            foreach ($this->fields as $field => $fieldData) {

                //$fieldType  = array_shift($types); // атрибут type в аннотации нам более не нужен
                if (!isset($fieldData['type'])) {

                    continue;
                }

                if (isset($fieldData['missWhenEditing']) && $param['edit']) {

                    continue;
                }

                if (isset($fieldData['missWhenCreating']) && !$param['edit']) {

                    continue;
                }

                // тип элемента
                $elementType         = $fieldData['type'];
                $elementForClassName = $elementType;

                // выпадающие списки пропускаем, для них надо настраивать мета-связи
                if ($elementType == 'selectStatic' || $elementType == 'selectDinamic') {

                    //var_dump($fieldData);
                    //die;
                    $elementForClassName = 'select';
                }

                $elementClassName = $this->classPrefix . ucfirst($elementForClassName);
                $fieldLabel       = isset($fieldData['label']) ? $fieldData['label'] : $this->get($field)->getName();

                // лишние
                unset($fieldData['type'], $fieldData['label']);

                $elementParams = $fieldData + ['placeholder' => $fieldLabel];

                // дя выпадающих списков свои донастройки
                if ($elementType == 'selectStatic') {

                    unset($fieldData['placeholder']);
                    $elementParams = $fieldData['options'];
                    $this->add(new $elementClassName($field, $elementParams));
                } else {
                    if ($elementType == 'selectDinamic') {

                        unset($fieldData['placeholder']);

                        $using = $fieldData['using'];

                        $resultsetInterface = call_user_func($fieldData['options']);

                        $this->add(
                            new $elementClassName(
                                $field, $resultsetInterface, [
                                    'using' => $using
                                ]
                            )
                        );
                    } else {

                        $this->add(new $elementClassName($field, $elementParams));
                    }
                }

                //устанавливаем label если поле не скрыто
                if (strtolower($elementType) !== 'hidden') {

                    $this->get($field)->setLabel($fieldLabel);
                }
            }
        }
    }
}
