<?php
require_once("report.php");
class Summary_customers extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_customer'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'));
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('CONCAT(first_name, " ",last_name) as customer, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax', false);
		$this->db->from('sales_items_temp');
		$this->db->join('customers', 'customers.person_id = sales_items_temp.customer_id');
		$this->db->join('people', 'customers.person_id = people.person_id');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->group_by('customer_id');
		$this->db->order_by('last_name');

		return $this->db->get()->result_array();		
	}
	
	public function getSummaryData(array $inputs)
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax');
		$this->db->from('sales_items_temp');
		$this->db->join('customers', 'customers.person_id = sales_items_temp.customer_id');
		$this->db->join('people', 'customers.person_id = people.person_id');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');

		return $this->db->get()->row_array();
	}
}
?>