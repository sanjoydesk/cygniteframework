namespace {%Apps%}\Form;

use Cygnite\FormBuilder\Form;
use Cygnite\Foundation\Application;
use Cygnite\Common\UrlManager\Url;

/**
* Sample Form using Cygnite Form Builder
* This file generated by Cygnite CLI Generator.
* You may alter the code to fit your needs
*/

class %controllerName%Form extends Form
{
    //set model object
    private $model;

    public $errors;

    private $segment;

    // We will set action url
    public $action = 'add';

    public function __construct($object = null, $segment = null)
    {
        // your model object
        $this->model = $object;
        $this->segment = $segment;
    }

    /**
    * Set validator used for displaying validation
    * errors below each form elements
    *
    * @param $validator
    * @return $this
    */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     *  Build form and return object
     * @return %controllerName%Form
     */
    public function buildForm()
    {
        $id = (isset($this->model->id)) ? $this->model->id : '';

        // Below code is to display validation errors below the input box
        if (is_object($this->validator)) {
            // Set your custom errors
            //$this->validator->setCustomError('column.error', 'Your Custom Error Message');
        }

        {%formElements%}

        return $this;
    }

    /**
     * Render form
     * @return type
     */
    public function render()
    {
        return $this->buildForm()->getForm();
    }
}