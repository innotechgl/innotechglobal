<?php

class validator_class extends util_class
{

    # Predefined validations
    public $error_log = array();
    private $validation_types = array(
        "date_sr" => "validate_date",
        "number" => "validate_number",
        "mail" => "validate_mail",
        "requested" => "validate_required",
        "minlength" => "min_length",
        "maxlength" => "max_length",
        "textonly" => "validate_text_only"
    );
    private $checking_field = '';

    public function  __construct()
    {
        
    }

    public function validate_all($requested_validations = array(), $vars = array())
    {
        # Go through requested 
        foreach ($requested_validations as $key => $val) {
            # Set checking field
            $this->checking_field = $key;
            #echo "Cekiram polje > ".$this->checking_field."<br />";
            # check if its array
            if (is_array($val)) {
                # Go through child array
                foreach ($val as $key_c => $val_c) {
                    # if it has more values
                    if (!is_int($key_c)) {
                        $func = $this->validation_types[$key_c];
                        # call function (this must be function with 2 values!)
                        $this->$func($vars[$key], $val_c);
                    } else {
                        # Set function name
                        $func = $this->validation_types[$val_c];
                        # call function (this must be function with 2 values!)
                        #echo 'trazim varijablu: '.$vars[$key]."<br />";
                        $this->$func($vars[$key]);
                    }
                }
            } # if it's not array
            else {
                # Set function name
                $func = $this->validation_types[$val];
                # call function (this must be function with 2 values!)
                #echo 'trazim varijablu: '.$vars[$key]."<br />";
                $this->$func($vars[$key]);
            }
        }
        # Check number of errors
        if (count($this->error_log) > 0) {
            # DEBUG
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $var_one
     * @param string $var_two
     * @return bool
     */
    public function compare_vars($var_one, $var_two)
    {
        # Check variables
        if (md5($var_one) == md5($var_two)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @name Add custom validations
     * @param array $array
     */
    private function add_custom_validations($array = array())
    {
        foreach ($array as $key => $val) {
            # add new validation
            $this->validation_types[$key] = $val;
        }
    }

    /**
     * @name Validate variable
     * @param string $type
     * @param string $val
     * @return bool
     */
    private function validate_date($type = 'string', $val = 'string')
    {
        # check out variable
        $result = preg_match($this->validation_types[$type], $val);
        #return result
        return $result;
    }

    private function validate_number($val = '')
    {
        if (!preg_match("/[0-9]/", $val)) {
            $this->error_log[$this->checking_field] = 'Ne sadrzi brojeve';
        }
        if (!preg_match("/[a-z]/", $val)) {
            $this->error_log[$this->checking_field] = 'Sadrzi slova';
        }
    }

    private function validate_text_only($val = '', $customTextBrojevi = 'Sadrzi brojeve', $customTextSlova = 'Ne sadrzi slova!')
    {
        if (preg_match("/[0-9]/", $val)) {
            $this->error_log[$this->checking_field] = $customTextBrojevi;
        }
        if (!preg_match("/[a-z]/", $val)) {
            $this->error_log[$this->checking_field] = $customTextSlova;
        }
    }

    private function validate_mail($val = 'string', $customText = 'Mail nije validan')
    {
        if (!preg_match("/[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]/i", $val)) {
            $this->error_log[$this->checking_field] = $customText;
        }
    }

    private function validate_required($val = 'string', $customText = 'Polje je obavezno!')
    {
        if (strlen(trim($val)) <= 0) {
            $this->error_log[$this->checking_field] = $customText;
        }
    }

    private function min_length($val = 'string', $min = 0, $customText = 'Nije ispostovan minimum ')
    {
        if (strlen($val) < $min) {
            $this->error_log[$this->checking_field] = $customText;
        }
    }

    private function max_length($val = 'string', $max = 0, $customText = 'Nije ispostovan maksimum')
    {
        if (strlen($val) > $max) {
            $this->error_log[$this->checking_field] = $customText;
        }
    }

    private function validate_custom($val = 'string', $regex = 'string')
    {
        if (!preg_match($regex, $val)) {
            $this->error_log[$this->checking_field] = 'greskaaaaa';
        }
    }
}

?>