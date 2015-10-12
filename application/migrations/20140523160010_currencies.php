<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_currencies extends CI_Migration {


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Add currencies table
	 *
	 */
	public function up()
	{
		echo "\n Adding currencies table";
		$query = $this->db->query("
			CREATE TABLE IF NOT EXISTS `currencies` (
			  `id` int(11) NOT NULL auto_increment,
			  `name` varchar(64) default NULL,
			  `code` char(3) default NULL,
			  `active` int(1) default 0,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
		");
		
		echo "\n Seeding currencies table";
		$query = $this->db->query("
			INSERT INTO `currencies` (`id`, `name`, `code`, `active`) VALUES
			(1, 'Andorran Peseta', 'ADP', 0),
			(2, 'United Arab Emirates Dirham', 'AED', 0),
			(3, 'Afghanistan Afghani', 'AFA', 0),
			(4, 'Albanian Lek', 'ALL', 0),
			(5, 'Netherlands Antillian Guilder', 'ANG', 0),
			(6, 'Angolan Kwanza', 'AOK', 0),
			(7, 'Argentine Peso', 'ARS', 1),
			(9, 'Australian Dollar', 'AUD', 1),
			(10, 'Aruban Florin', 'AWG', 0),
			(11, 'Barbados Dollar', 'BBD', 0),
			(12, 'Bangladeshi Taka', 'BDT', 0),
			(14, 'Bulgarian Lev', 'BGN', 0),
			(15, 'Bahraini Dinar', 'BHD', 0),
			(16, 'Burundi Franc', 'BIF', 0),
			(17, 'Bermudian Dollar', 'BMD', 0),
			(18, 'Brunei Dollar', 'BND', 0),
			(19, 'Bolivian Boliviano', 'BOB', 0),
			(20, 'Brazilian Real', 'BRL', 1),
			(21, 'Bahamian Dollar', 'BSD', 0),
			(22, 'Bhutan Ngultrum', 'BTN', 0),
			(23, 'Burma Kyat', 'BUK', 0),
			(24, 'Botswanian Pula', 'BWP', 0),
			(25, 'Belize Dollar', 'BZD', 0),
			(26, 'Canadian Dollar', 'CAD', 1),
			(27, 'Swiss Franc', 'CHF', 1),
			(28, 'Chilean Unidades de Fomento', 'CLF', 0),
			(29, 'Chilean Peso', 'CLP', 0),
			(30, 'Yuan (Chinese) Renminbi', 'CNY', 1),
			(31, 'Colombian Peso', 'COP', 0),
			(32, 'Costa Rican Colon', 'CRC', 0),
			(33, 'Czech Republic Koruna', 'CZK', 1),
			(34, 'Cuban Peso', 'CUP', 0),
			(35, 'Cape Verde Escudo', 'CVE', 0),
			(36, 'Cyprus Pound', 'CYP', 0),
			(40, 'Danish Krone', 'DKK', 1),
			(41, 'Dominican Peso', 'DOP', 0),
			(42, 'Algerian Dinar', 'DZD', 0),
			(43, 'Ecuador Sucre', 'ECS', 0),
			(44, 'Egyptian Pound', 'EGP', 1),
			(45, 'Estonian Kroon (EEK)', 'EEK', 0),
			(46, 'Ethiopian Birr', 'ETB', 0),
			(47, 'Euro', 'EUR', 1),
			(49, 'Fiji Dollar', 'FJD', 0),
			(50, 'Falkland Islands Pound', 'FKP', 0),
			(52, 'British Pound', 'GBP', 1),
			(53, 'Ghanaian Cedi', 'GHC', 1),
			(54, 'Gibraltar Pound', 'GIP', 0),
			(55, 'Gambian Dalasi', 'GMD', 1),
			(56, 'Guinea Franc', 'GNF', 0),
			(58, 'Guatemalan Quetzal', 'GTQ', 0),
			(59, 'Guinea-Bissau Peso', 'GWP', 0),
			(60, 'Guyanan Dollar', 'GYD', 0),
			(61, 'Hong Kong Dollar', 'HKD', 1),
			(62, 'Honduran Lempira', 'HNL', 0),
			(63, 'Haitian Gourde', 'HTG', 0),
			(64, 'Hungarian Forint', 'HUF', 1),
			(65, 'Indonesian Rupiah', 'IDR', 0),
			(66, 'Irish Punt', 'IEP', 0),
			(67, 'Israeli Shekel', 'ILS', 1),
			(68, 'Indian Rupee', 'INR', 1),
			(69, 'Iraqi Dinar', 'IQD', 0),
			(70, 'Iranian Rial', 'IRR', 0),
			(73, 'Jamaican Dollar', 'JMD', 0),
			(74, 'Jordanian Dinar', 'JOD', 0),
			(75, 'Japanese Yen', 'JPY', 1),
			(76, 'Kenyan Schilling', 'KES', 0),
			(77, 'Kampuchean (Cambodian) Riel', 'KHR', 0),
			(78, 'Comoros Franc', 'KMF', 0),
			(79, 'North Korean Won', 'KPW', 0),
			(80, '(South) Korean Won', 'KRW', 1),
			(81, 'Kuwaiti Dinar', 'KWD', 0),
			(82, 'Cayman Islands Dollar', 'KYD', 0),
			(83, 'Lao Kip', 'LAK', 0),
			(84, 'Lebanese Pound', 'LBP', 0),
			(85, 'Sri Lanka Rupee', 'LKR', 0),
			(86, 'Liberian Dollar', 'LRD', 0),
			(87, 'Lesotho Loti', 'LSL', 0),
			(89, 'Libyan Dinar', 'LYD', 0),
			(90, 'Moroccan Dirham', 'MAD', 1),
			(91, 'Malagasy Franc', 'MGF', 0),
			(92, 'Mongolian Tugrik', 'MNT', 0),
			(93, 'Macau Pataca', 'MOP', 0),
			(94, 'Mauritanian Ouguiya', 'MRO', 0),
			(95, 'Maltese Lira', 'MTL', 0),
			(96, 'Mauritius Rupee', 'MUR', 0),
			(97, 'Maldive Rufiyaa', 'MVR', 0),
			(98, 'Malawi Kwacha', 'MWK', 0),
			(99, 'Mexican Peso', 'MXP', 1),
			(100, 'Malaysian Ringgit', 'MYR', 0),
			(101, 'Mozambique Metical', 'MZM', 0),
			(102, 'Namibian Dollar', 'NAD', 0),
			(103, 'Nigerian Naira', 'NGN', 1),
			(104, 'Nicaraguan Cordoba', 'NIO', 0),
			(105, 'Norwegian Kroner', 'NOK', 1),
			(106, 'Nepalese Rupee', 'NPR', 0),
			(107, 'New Zealand Dollar', 'NZD', 1),
			(108, 'Omani Rial', 'OMR', 0),
			(109, 'Panamanian Balboa', 'PAB', 0),
			(110, 'Peruvian Nuevo Sol', 'PEN', 0),
			(111, 'Papua New Guinea Kina', 'PGK', 0),
			(112, 'Philippine Peso', 'PHP', 0),
			(113, 'Pakistan Rupee', 'PKR', 1),
			(114, 'Polish Zloty', 'PLN', 1),
			(116, 'Paraguay Guarani', 'PYG', 0),
			(117, 'Qatari Rial', 'QAR', 0),
			(118, 'Romanian Leu', 'RON', 1),
			(119, 'Rwanda Franc', 'RWF', 0),
			(120, 'Saudi Arabian Riyal', 'SAR', 0),
			(121, 'Solomon Islands Dollar', 'SBD', 0),
			(122, 'Seychelles Rupee', 'SCR', 0),
			(123, 'Sudanese Pound', 'SDP', 0),
			(124, 'Swedish Krona', 'SEK', 1),
			(125, 'Singapore Dollar', 'SGD', 1),
			(126, 'St. Helena Pound', 'SHP', 0),
			(127, 'Sierra Leone Leone', 'SLL', 0),
			(128, 'Somali Schilling', 'SOS', 0),
			(129, 'Suriname Guilder', 'SRG', 0),
			(130, 'Sao Tome and Principe Dobra', 'STD', 0),
			(131, 'Russian Ruble', 'RUB', 1),
			(132, 'El Salvador Colon', 'SVC', 0),
			(133, 'Syrian Potmd', 'SYP', 0),
			(134, 'Swaziland Lilangeni', 'SZL', 0),
			(135, 'Thai Baht', 'THB', 1),
			(136, 'Tunisian Dinar', 'TND', 1),
			(137, 'Tongan Paanga', 'TOP', 0),
			(138, 'East Timor Escudo', 'TPE', 0),
			(139, 'Turkish Lira', 'TRY', 1),
			(140, 'Trinidad and Tobago Dollar', 'TTD', 0),
			(141, 'Taiwan Dollar', 'TWD', 1),
			(142, 'Tanzanian Schilling', 'TZS', 0),
			(143, 'Uganda Shilling', 'UGX', 0),
			(144, 'US Dollar', 'USD', 1),
			(145, 'Uruguayan Peso', 'UYU', 0),
			(146, 'Venezualan Bolivar', 'VEF', 0),
			(147, 'Vietnamese Dong', 'VND', 0),
			(148, 'Vanuatu Vatu', 'VUV', 0),
			(149, 'Samoan Tala', 'WST', 0),
			(150, 'Communauté Financière Africaine BEAC, Francs', 'XAF', 1),
			(151, 'Silver, Ounces', 'XAG', 0),
			(152, 'Gold, Ounces', 'XAU', 0),
			(153, 'East Caribbean Dollar', 'XCD', 0),
			(154, 'International Monetary Fund (IMF) Special Drawing Rights', 'XDR', 0),
			(155, 'Communauté Financière Africaine BCEAO - Francs', 'XOF', 0),
			(156, 'Palladium Ounces', 'XPD', 0),
			(157, 'Comptoirs Français du Pacifique Francs', 'XPF', 0),
			(158, 'Platinum, Ounces', 'XPT', 0),
			(159, 'Democratic Yemeni Dinar', 'YDD', 0),
			(160, 'Yemeni Rial', 'YER', 0),
			(161, 'New Yugoslavia Dinar', 'YUD', 0),
			(162, 'South African Rand', 'ZAR', 1),
			(163, 'Zambian Kwacha', 'ZMK', 0),
			(164, 'Zaire Zaire', 'ZRZ', 0),
			(165, 'Zimbabwe Dollar', 'ZWD', 0),
			(166, 'Slovak Koruna', 'SKK', 1),
			(167, 'Armenian Dram', 'AMD', 0);
		");
		
		echo "\n Schema changes successful";
	}


	/**
	 * Drop currencies table
	 *
	 */
	public function down()
	{
		echo "\n Dropping currencies table";
		$this->dbforge->drop_table('currencies', TRUE);
		
		echo "\n Schema changes successful";
	}


}