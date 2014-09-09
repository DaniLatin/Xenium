<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

if (!isset($_SESSION['admin_user']))
{
    die();
}

$referrer = $_SERVER['HTTP_REFERER'];
$referrer_explode = explode('/', str_replace('http://', '', $referrer));
$referrer_domain = $referrer_explode[0];
$project_info = Projects::get_domain_project_info();
$project_id = $project_info['project_id'];
Domains::check_referrer_domain($project_id, $referrer_domain);

$date_range = sanitize($_GET['daterange']);
$get_date_data = explode(' - ', $date_range);
$date_from = $get_date_data[0];
$date_to = $get_date_data[1];

header('Content-disposition: attachment; filename="avantbon '.$date_from.' -- '.$date_to.'.xml"');
header('Content-type: "text/xml"; charset="utf8"');

$date_from = $date_from . ' 00:00:00';
$date_to = $date_to . ' 23:59:59';

$payment = new Payment;
$xdb_payments = new Xdb;

$xdb_payments_rows = $xdb_payments->select_fields('*')
                                  ->set_table($payment->table)
                                  //->where(array('status' => 'payed', 'AND::datetime_payed::>=' => $date_from, 'AND::datetime_payed::<' => $date_to))
                                  ->where(array('datetime_payed::>=' => $date_from, 'AND::datetime_payed::<' => $date_to, 'AND::status::!=' => 'pending'))
                                  //->and_where_group(array('status' => 'payed', 'OR::status' => 'storno', 'OR::status::=' => 'cm'))
                                  ->order_by('datetime_payed', 'ASC')
                                  //->limit($limit_start . ', ' . $limit)
                                  ->db_select(false);
//print_r($xdb_payments_rows);
?>
<?xml version="1.0" encoding="UTF-8"?>
<transactions>
    <?php foreach ($xdb_payments_rows as $row){ ?>
    <?php $user_info = json_decode($row['user_info'], true); unset($row['user_info']); $row['user_info'] = $user_info; ?>
    <?php $product_info = json_decode($row['product_info'], true); unset($row['product_info']); $row['product_info'] = $product_info; ?>
    <?php $pay_date_time = new DateTime($row['datetime_payed']); $paying_date_time = $pay_date_time->format('Y-m-d H:i:s'); $paying_date_time = str_replace(' ', 'T', $paying_date_time); $two_digit_year = $pay_date_time->format('y'); ?>
    <transaction>
		<project>avantbon</project>
		<customer_id><?php echo $row['user_id']; ?></customer_id>
		<nav_customer_id>75699-0000194</nav_customer_id>
		<customer_name><![CDATA[<?php echo $row['user_info']['name'] . ' ' . $row['user_info']['surname']; ?>]]></customer_name>
		<customer_address><![CDATA[<?php echo $row['user_info']['address']; ?>]]></customer_address>
		<customer_post_code><![CDATA[]]></customer_post_code>
		<customer_city><![CDATA[<?php echo $row['user_info']['city']; ?>]]></customer_city>
		<customer_country><?php echo $row['country']; ?></customer_country>
		<customer_email><![CDATA[<?php echo $row['user_info']['email']; ?>]]></customer_email>
		<date><?php echo $paying_date_time; ?></date>
		<date_service><?php echo $paying_date_time; ?></date_service>
		<shipment_date><?php echo $paying_date_time; ?></shipment_date>
		<vat_date><?php echo $paying_date_time; ?></vat_date>
		<output_vat_date><?php echo $paying_date_time; ?></output_vat_date>
		<invoice_id><?php if ($row['status'] == 'payed' || $row['status'] == 'storno'){ echo 'SIAV' . $two_digit_year . '-'; } else { echo 'SCAV' . $two_digit_year . '-'; } ?><?php echo $row['invoice_id']; ?></invoice_id>
		<apply_to_invoice_id><?php if ($row['apply_to_invoice_id'] && $row['status'] == 'cm') echo 'SIAV' . $two_digit_year . '-' . $row['apply_to_invoice_id']; ?></apply_to_invoice_id>
		<currency><?php //echo $row['currency']; ?>EUR</currency>
		<payment_terms_code>0d</payment_terms_code>
		<payment_mode><?php echo str_replace('paymentorder', 'payment_order', $row['payment_type']); ?></payment_mode>
		<reference_id><?php echo $row['reference']; ?></reference_id>
		<total_amount_net><?php echo number_format($row['discount_price'] / 1.08, 2); ?></total_amount_net>
		<total_amount_gross><?php echo number_format($row['discount_price'], 2); ?></total_amount_gross>
		<items>
			<item>
				<quantity>1</quantity>
				<amount_net><?php echo number_format($row['discount_price'] / 1.08, 2); ?></amount_net>
				<amount_gross><?php echo number_format($row['discount_price'], 2); ?></amount_gross>
				<offer_id><?php echo $row['product_id']; ?></offer_id>
				<offer_name><![CDATA[<?php echo $row['product_type'] . ' - ' . $row['product_info']['title']; ?>]]></offer_name>
			</item>	
		</items>
		<document_type><?php if ($row['status'] == 'payed' || $row['status'] == 'storno'){ ?>invoice<?php } elseif($row['status'] == 'cm'){ ?>credit memo<?php } ?></document_type>
	</transaction>
    <?php } ?>
</transactions>