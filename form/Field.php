<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc\form;



use lcgwhat\phpmvc\Model;

class Field
{
    /**
     * @var $model Model
     */
    public $model;
    /**
     * @var $attribute string
     */
    public $attribute;

    /**
     * @var string
     */
    private $part;
    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct( $model,  $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->textInput();

    }
    public function textarea($option = [])
    {

        $this->part = sprintf(' <textarea  name="%s" 
                               class="form-control %s" >%s</textarea>' ,

            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute},);

        return $this;
    }

    public function textInput($option = [])
    {
        $type = 'text';
        if (isset($option['type'])) {
            $type = $option['type'];
        }
        $this->part = sprintf(' <input type="%s" name="%s" value="%s"
                               class="form-control %s" >' ,
            $type,
            $this->attribute,
            $this->model->{$this->attribute},$this->model->hasError($this->attribute) ? ' is-invalid' : '',);

        return $this;
    }

    public function __toString()
    {
        return sprintf(
            ' <div class="form-group">
                        <label>%s</label>
                       %s
                        <div class="invalid-feedback">
                            %s
                        </div>
                    </div>',
            $this->model->getLabel($this->attribute),
            $this->part,
            $this->model->getFirstError($this->attribute)
        );
    }
}
