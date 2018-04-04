<?php  if (!defined('CDN_URL')) exit('No direct script access allowed');
/**
 *
 */

require 'StateConstants.php';

class StateTaxCalculatorModel extends TaxCalculatorModel {

    public function get_state_data($year, $state_abbr) {
        $state = StateConstants::abbreviation_to_full_name($state_abbr);
        
        $response = array();
        if (!in_array($year, $this->supported_years)) {
            $response['success'] = false;
            $response['reason'] = "Invalid year";
        } else if ($state === false) {
            $response['success'] = false;
            $response['reason'] = "Invalid state";
        } else {
            $state = strtolower(StateConstants::abbreviation_to_full_name($state_abbr));
            $state = str_replace(" ", "_", $state);
            
            $handle = file_get_contents(CDN_URL . "tax_tables/" . $year . "/" . $state . ".json", "r");
            $tax_table = json_decode($handle);
            
            $response['success'] = true;
            $response['data'] = $tax_table;
        }

        return $response;
    }

    public function calculate($year, $pay_rate, $pay_periods, $filing_status, $state) {
        $income = $pay_rate * $pay_periods;

            

        $data['state']['amount'] = $this->get_state_tax_amount($year, $income, $filing_status, $state);
        
        $response['data'] = $data;

        return $response;
    }

    /*
     *
     *
    */
    public function get_state_tax_amount($year, $income, $filing_status, $state_abbr) {
        $state_data = $this->get_state_data($year, $state_abbr);
        $target_table = $state_data['data']->$filing_status;
        
        $deduction_amount = 0;
        if (isset($target_table->deductions)) {
            foreach ($target_table->deductions as $deduction) {
                $deduction_amount+= $deduction->deduction_amount;
            }

        }
        $exemption_amount = 0;
        if (isset($target_table->exemptions)) {
            //$exemptions = $target_table->exemptions->personal;

        }
        $adjusted_income = $income - $deduction_amount - $exemption_amount;

        if ($adjusted_income < 0) {
            $adjusted_income = 0;
        }

        if (isset($target_table->type)
            && ($target_table->type == "none" || $target_table->type == "TODO UNKNOWN!")) {
            return null;
        } else {
            $amount = 0;
            for ($mridx = 0;$mridx < sizeof($target_table->income_tax_brackets);$mridx++) {
                $tax_bracket = $target_table->income_tax_brackets[$mridx];
                if ($mridx == (sizeof($target_table->income_tax_brackets) - 1)) {
                    $amount+= ($adjusted_income - $tax_bracket->bracket) * ($tax_bracket->marginal_rate / 100);
                } else if (($adjusted_income < $target_table->income_tax_brackets[$mridx + 1]->bracket)) {
                    $amount+= ($adjusted_income - $tax_bracket->bracket) * ($tax_bracket->marginal_rate / 100);
                    break;
                } else {
                    $amount+= ($target_table->income_tax_brackets[$mridx + 1]->bracket - $tax_bracket->bracket) * ($tax_bracket->marginal_rate / 100);
                }
            }

            return (float)number_format($amount, 2, '.', '');
        }

        return null;
    }
}
