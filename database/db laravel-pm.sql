-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2016 at 04:40 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `laravel-pm`
--

-- --------------------------------------------------------

--
-- Table structure for table `fp_accounts`
--

CREATE TABLE IF NOT EXISTS `fp_accounts` (
`id` int(10) unsigned NOT NULL,
  `account_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method_id` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_assigned_roles`
--

CREATE TABLE IF NOT EXISTS `fp_assigned_roles` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_assigned_user`
--

CREATE TABLE IF NOT EXISTS `fp_assigned_user` (
`id` int(11) NOT NULL,
  `belongs_to` varchar(100) CHARACTER SET latin1 NOT NULL,
  `unique_id` int(11) NOT NULL,
  `username` text CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_assigned_user`
--

INSERT INTO `fp_assigned_user` (`id`, `belongs_to`, `unique_id`, `username`, `created_at`, `updated_at`) VALUES
(3, 'bug', 1, 'ab', '2016-03-15 06:12:59', '2016-03-15 06:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `fp_attachment`
--

CREATE TABLE IF NOT EXISTS `fp_attachment` (
`attachment_id` int(11) NOT NULL,
  `attachment_title` varchar(100) NOT NULL,
  `attachment_description` text NOT NULL,
  `belongs_to` varchar(100) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `file` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_billing`
--

CREATE TABLE IF NOT EXISTS `fp_billing` (
`billing_id` int(11) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `client_id` int(11) NOT NULL,
  `issue_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `due_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `valid_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tax` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `notes` text,
  `billing_type` enum('estimate','invoice') NOT NULL,
  `invoiced_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_billing`
--

INSERT INTO `fp_billing` (`billing_id`, `ref_no`, `client_id`, `issue_date`, `due_date`, `valid_date`, `tax`, `discount`, `currency`, `notes`, `billing_type`, `invoiced_on`, `created_at`, `updated_at`) VALUES
(1, '1231', 1, '2016-03-18 16:00:00', '0000-00-00 00:00:00', '2016-03-31 16:00:00', '0.00', '0.00', 'usd', 'the estimate test', 'estimate', NULL, '2016-03-16 06:26:40', '2016-03-16 06:26:40'),
(2, '12321', 1, '2016-03-15 16:00:00', '2016-03-16 16:00:00', '0000-00-00 00:00:00', '0.00', '0.00', 'usd', '', 'invoice', NULL, '2016-03-16 06:33:12', '2016-03-16 06:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `fp_bug`
--

CREATE TABLE IF NOT EXISTS `fp_bug` (
`bug_id` int(11) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `project_id` int(11) NOT NULL,
  `bug_priority` enum('low','medium','high','critical') NOT NULL,
  `bug_description` text,
  `bug_status` enum('unconfirmed','confirmed','progress','resolved') NOT NULL,
  `reported_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_bug`
--

INSERT INTO `fp_bug` (`bug_id`, `ref_no`, `project_id`, `bug_priority`, `bug_description`, `bug_status`, `reported_on`, `created_at`, `updated_at`) VALUES
(1, '2334', 1, 'high', 'project controller bugs', 'progress', '2016-02-14 16:00:00', '2016-03-15 06:09:34', '2016-03-17 12:28:49');

-- --------------------------------------------------------

--
-- Table structure for table `fp_client`
--

CREATE TABLE IF NOT EXISTS `fp_client` (
`client_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text,
  `zipcode` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_client`
--

INSERT INTO `fp_client` (`client_id`, `company_name`, `contact_person`, `email`, `phone`, `address`, `zipcode`, `city`, `state`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'HDEnergy', 'ralph', 'ralph@gmail.com', '123123123', 'asdfasdfsdf sdf a', '1023', 'davao city', 'asdf asdf', 185, '2016-03-14 17:43:15', '2016-03-14 09:43:15'),
(2, 'Test', 'Test', 'test@gmail.com', '1234567890', 'Test', '2600', 'Baguio City', 'Benguet', 185, '2016-04-27 00:18:32', '2016-04-27 00:18:32'),
(3, 'Test2', '1234567890', 'jexterdeanbuenaventura@gmail.com', '1234567890', 'Test', '2600', 'Baguio City', 'Benguet', 185, '2016-04-27 01:51:07', '2016-04-27 01:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `fp_comment`
--

CREATE TABLE IF NOT EXISTS `fp_comment` (
`comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `belongs_to` varchar(10) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_comment`
--

INSERT INTO `fp_comment` (`comment_id`, `user_id`, `belongs_to`, `unique_id`, `comment`, `created_at`, `updated_at`) VALUES
(2, 0, 'bug', 1, 'How it is going, ab ?', '2016-03-15 06:13:14', '2016-03-15 06:13:14');

-- --------------------------------------------------------

--
-- Table structure for table `fp_companies`
--

CREATE TABLE IF NOT EXISTS `fp_companies` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `number_of_employees` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_companies`
--

INSERT INTO `fp_companies` (`id`, `name`, `email`, `phone`, `number_of_employees`, `address_1`, `address_2`, `province`, `zipcode`, `website`, `country_id`, `created_at`, `updated_at`) VALUES
(1, 'HD Energy', 'sales@hdenergy.ca', '1234567890', '1', 'Ontario', NULL, NULL, NULL, NULL, 15, '2016-05-11 06:34:13', '0000-00-00 00:00:00'),
(2, 'Test', 'test@gmail.com', '123456789', '1', 'Test', NULL, NULL, NULL, NULL, 15, '2016-05-12 05:29:02', '2016-05-12 05:29:02'),
(3, 'Test2', 'test2@gmail.com', 'test2@gmail.com', '1', '1234567890', NULL, NULL, NULL, NULL, 15, '2016-05-12 05:31:14', '2016-05-12 05:31:14'),
(4, 'Test 3', 'test3@gmail.com', '1234567890', '1', '', NULL, NULL, NULL, NULL, 15, '2016-05-15 20:26:32', '2016-05-15 20:26:32'),
(5, 'Test 4', 'test4@gmail.com', '12345656890', '1', 'Test', NULL, NULL, NULL, NULL, 15, '2016-05-15 20:29:53', '2016-05-15 20:29:53');

-- --------------------------------------------------------

--
-- Table structure for table `fp_company_divisions`
--

CREATE TABLE IF NOT EXISTS `fp_company_divisions` (
`id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `division_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_company_divisions`
--

INSERT INTO `fp_company_divisions` (`id`, `company_id`, `division_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Oshawa', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `fp_country`
--

CREATE TABLE IF NOT EXISTS `fp_country` (
`country_id` int(11) unsigned NOT NULL,
  `country_name` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=258 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_country`
--

INSERT INTO `fp_country` (`country_id`, `country_name`) VALUES
(1, 'Afghanistan'),
(2, 'Akrotiri'),
(3, 'Albania'),
(4, 'Algeria'),
(5, 'American Samoa'),
(6, 'Andorra'),
(7, 'Angola'),
(8, 'Anguilla'),
(9, 'Antarctica'),
(10, 'Antigua and Barbuda'),
(11, 'Argentina'),
(12, 'Armenia'),
(13, 'Aruba'),
(14, 'Ashmore and Cartier Islands'),
(15, 'Australia'),
(16, 'Austria'),
(17, 'Azerbaijan'),
(18, 'Bahamas, The'),
(19, 'Bahrain'),
(20, 'Bangladesh'),
(21, 'Barbados'),
(22, 'Bassas da India'),
(23, 'Belarus'),
(24, 'Belgium'),
(25, 'Belize'),
(26, 'Benin'),
(27, 'Bermuda'),
(28, 'Bhutan'),
(29, 'Bolivia'),
(30, 'Bosnia and Herzegovina'),
(31, 'Botswana'),
(32, 'Bouvet Island'),
(33, 'Brazil'),
(34, 'British Indian Ocean Territory'),
(35, 'British Virgin Islands'),
(36, 'Brunei'),
(37, 'Bulgaria'),
(38, 'Burkina Faso'),
(39, 'Burma'),
(40, 'Burundi'),
(41, 'Cambodia'),
(42, 'Cameroon'),
(43, 'Canada'),
(44, 'Cape Verde'),
(45, 'Cayman Islands'),
(46, 'Central African Republic'),
(47, 'Chad'),
(48, 'Chile'),
(49, 'China'),
(50, 'Christmas Island'),
(51, 'Clipperton Island'),
(52, 'Cocos (Keeling) Islands'),
(53, 'Colombia'),
(54, 'Comoros'),
(55, 'Congo, Democratic Republic of the'),
(56, 'Congo, Republic of the'),
(57, 'Cook Islands'),
(58, 'Coral Sea Islands'),
(59, 'Costa Rica'),
(60, 'Cote d''Ivoire'),
(61, 'Croatia'),
(62, 'Cuba'),
(63, 'Cyprus'),
(64, 'Czech Republic'),
(65, 'Denmark'),
(66, 'Dhekelia'),
(67, 'Djibouti'),
(68, 'Dominica'),
(69, 'Dominican Republic'),
(70, 'Ecuador'),
(71, 'Egypt'),
(72, 'El Salvador'),
(73, 'Equatorial Guinea'),
(74, 'Eritrea'),
(75, 'Estonia'),
(76, 'Ethiopia'),
(77, 'Europa Island'),
(78, 'Falkland Islands (Islas Malvinas)'),
(79, 'Faroe Islands'),
(80, 'Fiji'),
(81, 'Finland'),
(82, 'France'),
(83, 'French Guiana'),
(84, 'French Polynesia'),
(85, 'French Southern and Antarctic Lands'),
(86, 'Gabon'),
(87, 'Gambia, The'),
(88, 'Gaza Strip'),
(89, 'Georgia'),
(90, 'Germany'),
(91, 'Ghana'),
(92, 'Gibraltar'),
(93, 'Glorioso Islands'),
(94, 'Greece'),
(95, 'Greenland'),
(96, 'Grenada'),
(97, 'Guadeloupe'),
(98, 'Guam'),
(99, 'Guatemala'),
(100, 'Guernsey'),
(101, 'Guinea'),
(102, 'Guinea-Bissau'),
(103, 'Guyana'),
(104, 'Haiti'),
(105, 'Heard Island and McDonald Islands'),
(106, 'Holy See (Vatican City)'),
(107, 'Honduras'),
(108, 'Hong Kong'),
(109, 'Hungary'),
(110, 'Iceland'),
(111, 'India'),
(112, 'Indonesia'),
(113, 'Iran'),
(114, 'Iraq'),
(115, 'Ireland'),
(116, 'Isle of Man'),
(117, 'Israel'),
(118, 'Italy'),
(119, 'Jamaica'),
(120, 'Jan Mayen'),
(121, 'Japan'),
(122, 'Jersey'),
(123, 'Jordan'),
(124, 'Juan de Nova Island'),
(125, 'Kazakhstan'),
(126, 'Kenya'),
(127, 'Kiribati'),
(128, 'Korea, North'),
(129, 'Korea, South'),
(130, 'Kuwait'),
(131, 'Kyrgyzstan'),
(132, 'Laos'),
(133, 'Latvia'),
(134, 'Lebanon'),
(135, 'Lesotho'),
(136, 'Liberia'),
(137, 'Libya'),
(138, 'Liechtenstein'),
(139, 'Lithuania'),
(140, 'Luxembourg'),
(141, 'Macau'),
(142, 'Macedonia'),
(143, 'Madagascar'),
(144, 'Malawi'),
(145, 'Malaysia'),
(146, 'Maldives'),
(147, 'Mali'),
(148, 'Malta'),
(149, 'Marshall Islands'),
(150, 'Martinique'),
(151, 'Mauritania'),
(152, 'Mauritius'),
(153, 'Mayotte'),
(154, 'Mexico'),
(155, 'Micronesia, Federated States of'),
(156, 'Moldova'),
(157, 'Monaco'),
(158, 'Mongolia'),
(159, 'Montserrat'),
(160, 'Morocco'),
(161, 'Mozambique'),
(162, 'Namibia'),
(163, 'Nauru'),
(164, 'Navassa Island'),
(165, 'Nepal'),
(166, 'Netherlands'),
(167, 'Netherlands Antilles'),
(168, 'New Caledonia'),
(169, 'New Zealand'),
(170, 'Nicaragua'),
(171, 'Niger'),
(172, 'Nigeria'),
(173, 'Niue'),
(174, 'Norfolk Island'),
(175, 'Northern Mariana Islands'),
(176, 'Norway'),
(177, 'Oman'),
(178, 'Pakistan'),
(179, 'Palau'),
(180, 'Panama'),
(181, 'Papua New Guinea'),
(182, 'Paracel Islands'),
(183, 'Paraguay'),
(184, 'Peru'),
(185, 'Philippines'),
(186, 'Pitcairn Islands'),
(187, 'Poland'),
(188, 'Portugal'),
(189, 'Puerto Rico'),
(190, 'Qatar'),
(191, 'Reunion'),
(192, 'Romania'),
(193, 'Russia'),
(194, 'Rwanda'),
(195, 'Saint Helena'),
(196, 'Saint Kitts and Nevis'),
(197, 'Saint Lucia'),
(198, 'Saint Pierre and Miquelon'),
(199, 'Saint Vincent and the Grenadines'),
(200, 'Samoa'),
(201, 'San Marino'),
(202, 'Sao Tome and Principe'),
(203, 'Saudi Arabia'),
(204, 'Senegal'),
(205, 'Serbia and Montenegro'),
(206, 'Seychelles'),
(207, 'Sierra Leone'),
(208, 'Singapore'),
(209, 'Slovakia'),
(210, 'Slovenia'),
(211, 'Solomon Islands'),
(212, 'Somalia'),
(213, 'South Africa'),
(214, 'South Georgia and the South Sandwich Islands'),
(215, 'Spain'),
(216, 'Spratly Islands'),
(217, 'Sri Lanka'),
(218, 'Sudan'),
(219, 'Suriname'),
(220, 'Svalbard'),
(221, 'Swaziland'),
(222, 'Sweden'),
(223, 'Switzerland'),
(224, 'Syria'),
(225, 'Taiwan'),
(226, 'Tajikistan'),
(227, 'Tanzania'),
(228, 'Thailand'),
(229, 'Timor-Leste'),
(230, 'Togo'),
(231, 'Tokelau'),
(232, 'Tonga'),
(233, 'Trinidad and Tobago'),
(234, 'Tromelin Island'),
(235, 'Tunisia'),
(236, 'Turkey'),
(237, 'Turkmenistan'),
(238, 'Turks and Caicos Islands'),
(239, 'Tuvalu'),
(240, 'Uganda'),
(241, 'Ukraine'),
(242, 'United Arab Emirates'),
(243, 'United Kingdom'),
(244, 'United States'),
(245, 'Uruguay'),
(246, 'Uzbekistan'),
(247, 'Vanuatu'),
(248, 'Venezuela'),
(249, 'Vietnam'),
(250, 'Virgin Islands'),
(251, 'Wake Island'),
(252, 'Wallis and Futuna'),
(253, 'West Bank'),
(254, 'Western Sahara'),
(255, 'Yemen'),
(256, 'Zambia'),
(257, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `fp_events`
--

CREATE TABLE IF NOT EXISTS `fp_events` (
`event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_title` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `event_description` text CHARACTER SET latin1,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `public` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_events`
--

INSERT INTO `fp_events` (`event_id`, `user_id`, `event_title`, `event_description`, `start_date`, `end_date`, `public`, `created_at`, `updated_at`) VALUES
(2, 6, 'esda', 'asdfsf', '2016-05-11 16:12:56', '2016-03-18 16:00:00', 0, '2016-03-17 00:07:34', '2016-03-17 00:07:34'),
(3, 6, 'sest', 'asdf', '2016-05-11 16:13:00', '2016-03-20 16:00:00', 0, '2016-03-17 01:54:42', '2016-03-17 01:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `fp_item`
--

CREATE TABLE IF NOT EXISTS `fp_item` (
`item_id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `item_description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_item`
--

INSERT INTO `fp_item` (`item_id`, `billing_id`, `item_name`, `item_quantity`, `unit_price`, `item_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Work1', '1.00', '10.00', NULL, '2016-03-16 06:31:28', '2016-03-16 06:31:28');

-- --------------------------------------------------------

--
-- Table structure for table `fp_links`
--

CREATE TABLE IF NOT EXISTS `fp_links` (
`id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `descriptions` text COLLATE utf8_unicode_ci NOT NULL,
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `task_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_links`
--

INSERT INTO `fp_links` (`id`, `title`, `url`, `descriptions`, `tags`, `comments`, `category_id`, `created_at`, `updated_at`, `task_id`) VALUES
(2, 'Test', 'test.com', 'Test', '', '', 0, '2016-04-27 01:45:16', '2016-04-27 01:45:16', 3),
(3, 'Test 2', 'test2.com', 'Test', '', '', 0, '2016-04-27 02:46:11', '2016-04-27 02:46:11', 3),
(4, 'Test', 'test.com', 'Test', '', '', 0, '2016-04-28 07:31:02', '2016-04-28 07:31:02', 6),
(5, 'Test 2', 'test2.com', 'Test', '', '', 0, '2016-04-28 07:33:58', '2016-04-28 07:33:58', 6);

-- --------------------------------------------------------

--
-- Table structure for table `fp_link_categories`
--

CREATE TABLE IF NOT EXISTS `fp_link_categories` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_link_tags`
--

CREATE TABLE IF NOT EXISTS `fp_link_tags` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_meeting`
--

CREATE TABLE IF NOT EXISTS `fp_meeting` (
`id` int(10) unsigned NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `type_id` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `estimated_length` double(8,2) NOT NULL,
  `priority_id` int(11) NOT NULL,
  `attendees` text COLLATE utf8_unicode_ci NOT NULL,
  `meeting_url` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_meeting`
--

INSERT INTO `fp_meeting` (`id`, `project_id`, `user_id`, `start_date`, `end_date`, `type_id`, `description`, `estimated_length`, `priority_id`, `attendees`, `meeting_url`, `created_at`, `updated_at`) VALUES
(1, 1, 14, '2016-04-06 00:00:00', '2016-04-06 12:59:00', 1, 'Test', 12.00, 1, '["8","9","15"]', 'test', '2016-04-26 02:42:03', '2016-04-26 05:01:05'),
(2, 1, 6, '2016-04-07 00:00:00', '2016-04-07 12:59:00', 1, 'Test', 12.00, 2, '["6"]', 'Test', '2016-04-26 03:07:01', '2016-04-26 03:07:01'),
(3, 1, 6, '2016-04-05 00:00:00', '2016-04-05 12:59:00', 1, 'Test', 12.00, 1, '["15"]', 'test', '2016-04-26 03:11:05', '2016-04-26 03:11:05'),
(4, 2, 6, '2016-04-08 00:00:00', '2016-04-08 12:59:00', 2, 'Test', 30.00, 2, '["14","15","16"]', '', '2016-04-26 23:35:29', '2016-04-26 23:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `fp_meeting_priority`
--

CREATE TABLE IF NOT EXISTS `fp_meeting_priority` (
`id` int(10) unsigned NOT NULL,
  `priority` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_meeting_priority`
--

INSERT INTO `fp_meeting_priority` (`id`, `priority`, `created_at`, `updated_at`) VALUES
(1, 'Normal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Urgent', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `fp_meeting_type`
--

CREATE TABLE IF NOT EXISTS `fp_meeting_type` (
`id` int(10) unsigned NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_meeting_type`
--

INSERT INTO `fp_meeting_type` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'In Person', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Online', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Telephone', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `fp_message`
--

CREATE TABLE IF NOT EXISTS `fp_message` (
`message_id` int(11) NOT NULL,
  `message_subject` text NOT NULL,
  `message_content` text NOT NULL,
  `file` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `to_user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_message`
--

INSERT INTO `fp_message` (`message_id`, `message_subject`, `message_content`, `file`, `created_at`, `updated_at`, `to_user_id`, `from_user_id`) VALUES
(1, 'hey', 'hey buddy<br>', '', '2016-03-16 16:44:12', '2016-03-16 16:44:12', 0, 0),
(2, 'Test', 'Test', '', '2016-04-26 23:34:49', '2016-04-26 23:34:49', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fp_migrations`
--

CREATE TABLE IF NOT EXISTS `fp_migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_newnote`
--

CREATE TABLE IF NOT EXISTS `fp_newnote` (
`note_id` int(10) unsigned NOT NULL,
  `belongs_to` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `unique_id` int(11) NOT NULL,
  `note_content` text COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_notes`
--

CREATE TABLE IF NOT EXISTS `fp_notes` (
`note_id` int(11) NOT NULL,
  `belongs_to` varchar(20) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `note_content` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_notes`
--

INSERT INTO `fp_notes` (`note_id`, `belongs_to`, `unique_id`, `note_content`, `username`, `created_at`, `updated_at`) VALUES
(2, 'billing', 1, 'this is total a total <b>amount</b>.', 'admin', '2016-03-16 06:28:35', '2016-03-16 06:28:35'),
(3, 'project', 2, 'Test 2', '', '2016-05-12 11:19:25', '2016-05-12 03:19:25'),
(4, 'project', 2, 'Testing', 'tomcoghill', '2016-05-10 10:47:49', '2016-05-10 02:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `fp_password_resets`
--

CREATE TABLE IF NOT EXISTS `fp_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_payment`
--

CREATE TABLE IF NOT EXISTS `fp_payment` (
`payment_id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','bank') NOT NULL,
  `payment_notes` text,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_payment_method`
--

CREATE TABLE IF NOT EXISTS `fp_payment_method` (
`id` int(10) unsigned NOT NULL,
  `payment_method` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_pay_period`
--

CREATE TABLE IF NOT EXISTS `fp_pay_period` (
`id` int(10) unsigned NOT NULL,
  `period` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_permissions`
--

CREATE TABLE IF NOT EXISTS `fp_permissions` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_permission_role`
--

CREATE TABLE IF NOT EXISTS `fp_permission_role` (
`id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_permission_user`
--

CREATE TABLE IF NOT EXISTS `fp_permission_user` (
`id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_profiles`
--

CREATE TABLE IF NOT EXISTS `fp_profiles` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_profiles`
--

INSERT INTO `fp_profiles` (`id`, `user_id`, `company_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 18, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 41, 2, 1, '2016-05-17 00:03:22', '2016-05-17 00:03:22'),
(22, 42, 1, 1, '2016-05-17 00:06:31', '2016-05-17 00:06:31');

-- --------------------------------------------------------

--
-- Table structure for table `fp_project`
--

CREATE TABLE IF NOT EXISTS `fp_project` (
`project_id` int(11) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `project_title` varchar(500) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deadline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `project_description` text,
  `rate_type` enum('fixed','hourly') NOT NULL,
  `rate_value` float NOT NULL,
  `project_progress` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `account` varchar(100) NOT NULL,
  `reverence` text NOT NULL,
  `currency` text NOT NULL,
  `project_type` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_project`
--

INSERT INTO `fp_project` (`project_id`, `company_id`, `user_id`, `ref_no`, `project_title`, `start_date`, `deadline`, `project_description`, `rate_type`, `rate_value`, `project_progress`, `created_at`, `updated_at`, `account`, `reverence`, `currency`, `project_type`) VALUES
(2, 1, 18, '2', 'Test', '2016-05-13 14:01:53', '2016-10-05 16:00:00', 'Test', 'fixed', 12, 0, '2016-04-26 05:53:01', '2016-04-26 05:53:01', '123456', 'Test', 'USD', 'Standard'),
(5, 1, 18, '5', 'Jex Project Test 1', '2016-05-17 04:08:53', '2016-10-05 16:00:00', 'Test', 'fixed', 20, 0, '2016-05-16 20:08:53', '2016-05-16 20:08:53', '123456', '', 'USD', 'Hiring Assessment'),
(6, 2, 18, '6', 'Test 1', '2016-05-17 07:45:08', '0000-00-00 00:00:00', 'Test 1', '', 0, 0, '2016-05-16 23:45:08', '2016-05-16 23:45:08', '', '', '', 'Software Development'),
(7, 1, 42, '7', 'Jane Doe Project', '2016-05-17 09:11:10', '2020-10-07 16:00:00', 'Jane Doe Project', 'hourly', 12, 0, '2016-05-17 01:11:10', '2016-05-17 01:11:10', '', '', '', 'Standard');

-- --------------------------------------------------------

--
-- Table structure for table `fp_question`
--

CREATE TABLE IF NOT EXISTS `fp_question` (
`id` int(10) unsigned NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_type_id` int(11) NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `question_choices` text COLLATE utf8_unicode_ci NOT NULL,
  `question_answer` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `length` time NOT NULL,
  `question_photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `score` int(11) NOT NULL,
  `order` double(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `points` double(8,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_question`
--

INSERT INTO `fp_question` (`id`, `test_id`, `question_type_id`, `question`, `question_choices`, `question_answer`, `length`, `question_photo`, `score`, `order`, `created_at`, `updated_at`, `points`) VALUES
(1, 1, 1, 'Test 2', '["a","b","c","d"]', '0', '00:10:00', '1.png', 0, 1.00, '2016-04-27 02:30:33', '2016-05-02 01:17:00', 0.00),
(2, 2, 1, 'Test', '["a","b","c","d"]', '2', '00:00:10', '', 0, 1.00, '2016-04-27 02:32:35', '2016-05-02 01:18:14', 0.00),
(3, 3, 1, 'Test', '["a","b","c","d"]', '2', '00:00:00', '', 0, 1.00, '2016-04-27 02:41:32', '2016-04-27 02:41:32', 0.00),
(4, 4, 1, 'Test', '["a","b","c","d"]', '2', '00:01:20', '', 0, 1.00, '2016-04-27 02:58:27', '2016-04-27 02:58:27', 0.00),
(5, 5, 1, 'Test', '["a","b","c","d"]', '3', '00:00:15', '', 0, 1.00, '2016-04-27 03:06:05', '2016-04-27 03:06:05', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `fp_question_feedback`
--

CREATE TABLE IF NOT EXISTS `fp_question_feedback` (
`id` int(10) unsigned NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_question_tag`
--

CREATE TABLE IF NOT EXISTS `fp_question_tag` (
`id` int(10) unsigned NOT NULL,
  `tag_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_question_type`
--

CREATE TABLE IF NOT EXISTS `fp_question_type` (
`id` int(10) unsigned NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_question_type`
--

INSERT INTO `fp_question_type` (`id`, `type`) VALUES
(1, 'Multiple Choice'),
(2, 'Fill in the Blank');

-- --------------------------------------------------------

--
-- Table structure for table `fp_roles`
--

CREATE TABLE IF NOT EXISTS `fp_roles` (
`id` int(10) unsigned NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `company_division_id` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_roles`
--

INSERT INTO `fp_roles` (`id`, `company_id`, `company_division_id`, `name`, `slug`, `description`, `level`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Admin', 'admin', 'Administrator', 1, '2016-04-26 02:35:11', '2016-04-26 02:35:11'),
(2, 1, 1, 'Client', 'client', 'Clients', 3, '2016-04-26 02:35:11', '2016-04-26 02:35:11'),
(3, 1, 1, 'Staff', 'staff', 'The staff', 2, '2016-04-26 02:35:11', '2016-04-26 02:35:11');

-- --------------------------------------------------------

--
-- Table structure for table `fp_role_user`
--

CREATE TABLE IF NOT EXISTS `fp_role_user` (
`id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_role_user`
--

INSERT INTO `fp_role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(8, 1, 18, '2016-05-09 05:44:24', '2016-05-09 05:44:24'),
(30, 1, 41, '2016-05-17 00:03:22', '2016-05-17 00:03:22'),
(31, 1, 42, '2016-05-17 00:06:31', '2016-05-17 00:06:31');

-- --------------------------------------------------------

--
-- Table structure for table `fp_setting`
--

CREATE TABLE IF NOT EXISTS `fp_setting` (
`id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `country_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `company_logo` varchar(100) DEFAULT NULL,
  `timezone_id` varchar(10) DEFAULT NULL,
  `default_currency` varchar(20) NOT NULL,
  `default_language` varchar(10) NOT NULL,
  `allowed_upload_file` text NOT NULL,
  `allowed_upload_max_size` int(11) NOT NULL,
  `default_tax` decimal(10,2) NOT NULL,
  `default_discount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_setting`
--

INSERT INTO `fp_setting` (`id`, `company_name`, `contact_person`, `address`, `city`, `state`, `zipcode`, `country_id`, `email`, `phone`, `company_logo`, `timezone_id`, `default_currency`, `default_language`, `allowed_upload_file`, `allowed_upload_max_size`, `default_tax`, `default_discount`, `created_at`, `updated_at`) VALUES
(1, 'Jobtc', 'admin', 'admin at admin building', 'davao', 'test', '1231', 185, 'admin@ab.com', '1212123456', NULL, '146', 'usd', 'en', 'txt,png,jpeg,jpg,zip,rar', 10, '0.00', '0.00', '2016-03-16 14:22:14', '2016-03-16 06:22:14');

-- --------------------------------------------------------

--
-- Table structure for table `fp_task`
--

CREATE TABLE IF NOT EXISTS `fp_task` (
`task_id` int(11) NOT NULL,
  `belongs_to` varchar(10) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `task_title` text NOT NULL,
  `task_description` text,
  `due_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_visible` enum('yes','no') NOT NULL,
  `task_status` enum('pending','progress','completed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_task`
--

INSERT INTO `fp_task` (`task_id`, `belongs_to`, `unique_id`, `task_title`, `task_description`, `due_date`, `is_visible`, `task_status`, `created_at`, `updated_at`, `user_id`, `project_id`) VALUES
(6, 'project', 2, 'Test 4 Test', 'Test 4', '2016-05-17 04:01:19', 'yes', 'pending', '2016-04-27 23:59:51', '2016-04-29 03:02:50', 18, 2),
(19, 'project', 2, 'Test 2', 'Test 2', '2016-05-17 04:04:40', 'yes', 'pending', '2016-05-06 00:03:24', '2016-05-06 00:03:24', 34, 2),
(20, 'project', 4, 'Test Task', 'Test ', '2016-05-17 04:04:44', 'yes', 'pending', '2016-05-15 20:35:35', '2016-05-15 20:35:35', 38, 4),
(21, 'project', 3, 'Testing tasklist for Test 2', 'Test', '2016-05-17 04:04:47', 'yes', 'pending', '2016-05-16 04:50:36', '2016-05-16 04:50:36', 39, 3),
(22, 'project', 5, 'Test 1', 'Test', '2016-05-17 04:20:22', 'yes', 'pending', '2016-05-16 20:19:22', '2016-05-16 20:19:22', 18, 5),
(23, 'project', 5, 'Test 2', 'Test 2', '0000-00-00 00:00:00', 'yes', 'pending', '2016-05-16 20:20:34', '2016-05-16 20:20:34', 18, 5),
(24, 'project', 5, 'Test 3', 'Test 3', '0000-00-00 00:00:00', 'yes', 'pending', '2016-05-16 20:20:44', '2016-05-16 20:20:44', 18, 5),
(25, 'project', 5, '1', 'Test', '0000-00-00 00:00:00', 'yes', 'pending', '2016-05-16 20:22:32', '2016-05-16 20:22:32', 18, 5);

-- --------------------------------------------------------

--
-- Table structure for table `fp_task_check_list`
--

CREATE TABLE IF NOT EXISTS `fp_task_check_list` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `checklist_header` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `checklist` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Default'
) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_task_check_list`
--

INSERT INTO `fp_task_check_list` (`id`, `user_id`, `task_id`, `checklist_header`, `checklist`, `created_at`, `updated_at`, `status`) VALUES
(179, 0, 5, 'Test', 'Test', '2016-05-03 00:35:50', '2016-05-03 03:58:58', 'Default'),
(180, 0, 4, 'Test', 'Test 2', '2016-05-03 00:35:58', '2016-05-03 03:14:05', 'Default'),
(182, 0, 3, 'Test', 'Test 4', '2016-05-03 01:29:37', '2016-05-03 01:29:37', 'Default'),
(183, 0, 3, 'Test', 'Test 5', '2016-05-03 01:29:42', '2016-05-03 03:03:22', 'Default'),
(184, 0, 3, 'Test', 'Test 6', '2016-05-03 03:03:27', '2016-05-03 03:13:51', 'Default'),
(185, 0, 3, 'Test', 'Test 7', '2016-05-03 03:11:23', '2016-05-03 03:11:23', 'Default'),
(186, 0, 3, 'Test', 'Test 8', '2016-05-03 03:12:16', '2016-05-03 03:12:16', 'Default'),
(187, 0, 3, 'Test', 'Test 9', '2016-05-03 03:13:05', '2016-05-03 03:13:05', 'Default'),
(189, 0, 3, 'Test', 'Test', '2016-05-04 01:50:09', '2016-05-04 01:50:09', 'Default'),
(190, 0, 3, 'Test', 'Test 7', '2016-05-04 01:50:16', '2016-05-04 01:50:16', 'Default'),
(193, 0, 19, 'Test 7', '<p><strong>Intro</strong><br />\nMake changes to Best Practices or ask for clarification by asking a question in the task items. Success is when a person can commit to the server and restore the previous version without assistance using Putty.<br />\nProject management is on pm/project/1</p>\n', '2016-05-04 03:31:52', '2016-05-06 03:00:57', 'Ongoing'),
(194, 0, 14, 'Test', '<div><div><div>                                                    <div>                                                    <div>                                                    Test 4                                                </div>                                                </div>                                                </div></div></div>', '2016-05-04 03:49:06', '2016-05-04 06:03:24', 'Default'),
(222, 0, 15, 'Test 2', '<div class="checklist-item"><p>Testing</p><p>Testing</p></div>', '2016-05-04 05:18:57', '2016-05-04 08:21:45', 'Completed'),
(224, 0, 19, 'Test 12', 'Test', '2016-05-04 05:29:47', '2016-05-06 03:01:01', 'Ongoing'),
(225, 0, 18, 'Test 8', 'Test 9', '2016-05-05 00:51:05', '2016-05-08 22:16:50', 'Ongoing'),
(226, 0, 19, 'Test 9', 'Test 9', '2016-05-05 01:04:07', '2016-05-06 01:32:49', 'Ongoing'),
(241, 0, 18, 'Test 10', '<p>Test 10</p>\n', '2016-05-05 04:02:49', '2016-05-06 01:33:18', 'Ongoing'),
(243, 0, 6, 'Test 12', '<p>Test 12</p>\n', '2016-05-05 06:06:25', '2016-05-13 07:35:56', 'Ongoing'),
(244, 0, 19, 'Test 13', '<p>Test 13</p>\n', '2016-05-05 06:35:36', '2016-05-06 01:32:47', 'Ongoing'),
(247, 0, 18, 'Testing 3', '<p><input name="test" type="checkbox" />Testing 3</p>\n', '2016-05-06 01:25:45', '2016-05-10 03:22:15', 'Ongoing'),
(258, 0, 18, 'Testing 4', '<p>Testing 4</p>\n', '2016-05-10 03:21:39', '2016-05-10 03:22:18', 'Default'),
(259, 0, 6, 'Testing 5', '<p>Testing 5</p>\n', '2016-05-10 03:21:48', '2016-05-13 07:35:50', 'Default'),
(260, 0, 6, 'Testing 6', '<p>Testing 6</p>\n', '2016-05-10 03:22:00', '2016-05-13 07:35:52', 'Default'),
(261, 0, 6, 'Testing 7', '<p>Testing 7</p>\n', '2016-05-10 04:22:25', '2016-05-12 06:26:52', 'Default'),
(262, 0, 6, 'Testing 8', '<p>Testing 8</p>\n', '2016-05-10 06:01:49', '2016-05-13 07:36:06', 'Default'),
(263, 0, 6, 'Testing 9', '<p>Testing 9</p>\n', '2016-05-10 09:38:14', '2016-05-10 09:38:14', 'Default'),
(264, 0, 6, 'Testing 10', '<p>Testing 10</p>\n', '2016-05-10 09:42:40', '2016-05-10 09:42:40', 'Default'),
(265, 0, 19, 'Testing 6', '<p>Testing 6</p>\n', '2016-05-12 22:48:35', '2016-05-12 22:48:35', 'Default'),
(266, 0, 6, 'Test', '<p>Test</p>\n', '2016-05-15 17:42:15', '2016-05-15 17:42:15', 'Default'),
(267, 0, 20, 'Test', '<p>Test</p>\n', '2016-05-16 03:30:35', '2016-05-16 03:30:35', 'Default'),
(269, 0, 20, 'Test 2', '<p>Test 2</p>\n', '2016-05-16 03:31:07', '2016-05-16 03:31:07', 'Default'),
(270, 0, 20, 'Test 3', '<p>Test 3</p>\n', '2016-05-16 03:31:24', '2016-05-16 03:31:41', 'Default'),
(271, 0, 20, 'Test 4', '<p>Test 4</p>\n', '2016-05-16 04:26:41', '2016-05-16 04:26:41', 'Default'),
(272, 0, 20, 'Test 5', '<p>Test 5</p>\n', '2016-05-16 04:27:04', '2016-05-16 04:27:04', 'Default'),
(273, 0, 20, 'Test 6', '<p>Test 6</p>\n', '2016-05-16 04:32:36', '2016-05-16 04:32:36', 'Default'),
(275, 0, 21, 'Test', '<p>Test</p>\n', '2016-05-16 04:50:45', '2016-05-16 04:50:45', 'Default'),
(277, 0, 21, 'Test', '<p>Test</p>\n', '2016-05-16 04:53:27', '2016-05-16 04:53:27', 'Default'),
(278, 18, 25, 'Test', '<p>Test</p>\n', '2016-05-17 02:57:39', '2016-05-17 02:57:39', 'Default');

-- --------------------------------------------------------

--
-- Table structure for table `fp_task_check_list_order`
--

CREATE TABLE IF NOT EXISTS `fp_task_check_list_order` (
`id` int(10) unsigned NOT NULL,
  `task_id` bigint(20) NOT NULL,
  `task_id_order` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_task_check_list_order`
--

INSERT INTO `fp_task_check_list_order` (`id`, `task_id`, `task_id_order`, `created_at`, `updated_at`) VALUES
(60, 14, '194,221', '2016-05-04 05:46:29', '2016-05-04 05:46:29'),
(259, 18, '225,241,258,247', '2016-05-10 03:22:18', '2016-05-10 03:22:18'),
(293, 19, '265,226,244,224,193', '2016-05-13 07:36:06', '2016-05-13 07:36:06'),
(296, 20, '269,267,270,271,272,273', '2016-05-16 19:41:48', '2016-05-16 19:41:48'),
(297, 6, '260,259,261,262,243,263,264,266', '2016-05-16 20:07:10', '2016-05-16 20:07:10');

-- --------------------------------------------------------

--
-- Table structure for table `fp_task_comment`
--

CREATE TABLE IF NOT EXISTS `fp_task_comment` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_task_timer`
--

CREATE TABLE IF NOT EXISTS `fp_task_timer` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_task_timer`
--

INSERT INTO `fp_task_timer` (`id`, `user_id`, `task_id`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 0, 2, '2016-04-26 21:12:08', '2016-04-26 21:12:30', '2016-04-26 05:12:09', '2016-04-26 05:12:30'),
(2, 0, 2, '2016-04-26 21:12:29', '2016-04-26 21:12:40', '2016-04-26 05:12:29', '2016-04-26 05:12:40'),
(5, 0, 3, '2016-04-27 19:58:52', '2016-04-27 19:59:05', '2016-04-27 03:58:52', '2016-04-27 03:59:06'),
(6, 0, 3, '2016-04-27 22:00:50', '2016-04-27 22:01:41', '2016-04-27 06:00:51', '2016-04-27 06:01:41'),
(7, 0, 3, '2016-04-27 22:02:14', '2016-04-27 22:02:19', '2016-04-27 06:02:14', '2016-04-27 06:02:20'),
(8, 0, 3, '2016-04-27 22:09:24', '2016-04-27 22:09:34', '2016-04-27 06:09:24', '2016-04-27 06:09:35'),
(10, 0, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016-05-02 03:19:25', '2016-05-02 03:19:25'),
(27, 0, 6, '0000-00-00 00:00:00', '2016-05-05 00:14:57', '2016-05-02 04:38:21', '2016-05-04 08:14:57'),
(28, 0, 6, '0000-00-00 00:00:00', '2016-05-05 00:15:04', '2016-05-02 04:46:05', '2016-05-04 08:15:04'),
(29, 0, 6, '2016-05-05 00:15:05', '2016-05-05 00:15:07', '2016-05-04 08:15:05', '2016-05-04 08:15:07'),
(30, 0, 6, '0000-00-00 00:00:00', '2016-05-05 21:46:19', '2016-05-05 01:33:15', '2016-05-05 05:46:19');

-- --------------------------------------------------------

--
-- Table structure for table `fp_team`
--

CREATE TABLE IF NOT EXISTS `fp_team` (
`id` int(10) unsigned NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_team`
--

INSERT INTO `fp_team` (`id`, `project_id`, `created_at`, `updated_at`) VALUES
(10, 6, '2016-05-17 00:04:19', '2016-05-17 00:04:19'),
(11, 2, '2016-05-17 00:07:41', '2016-05-17 00:07:41'),
(12, 5, '2016-05-17 01:03:50', '2016-05-17 01:03:50'),
(13, 7, '2016-05-17 01:15:41', '2016-05-17 01:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `fp_team_member`
--

CREATE TABLE IF NOT EXISTS `fp_team_member` (
`id` int(10) unsigned NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_team_project`
--

CREATE TABLE IF NOT EXISTS `fp_team_project` (
`id` int(10) unsigned NOT NULL,
  `team_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_team_project`
--

INSERT INTO `fp_team_project` (`id`, `team_id`, `project_id`, `created_at`, `updated_at`) VALUES
(8, 11, 2, '2016-05-17 00:08:16', '2016-05-17 00:08:16'),
(9, 12, 5, '2016-05-17 01:04:41', '2016-05-17 01:04:41'),
(10, 13, 7, '2016-05-17 01:16:10', '2016-05-17 01:16:10');

-- --------------------------------------------------------

--
-- Table structure for table `fp_template`
--

CREATE TABLE IF NOT EXISTS `fp_template` (
`template_id` int(11) NOT NULL,
  `template_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `template_subject` text CHARACTER SET latin1 NOT NULL,
  `template_content` text CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_test`
--

CREATE TABLE IF NOT EXISTS `fp_test` (
`id` int(10) unsigned NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `length` time NOT NULL,
  `version` double(8,2) NOT NULL,
  `average_score` double(8,2) NOT NULL,
  `test_photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completion_message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fp_test`
--

INSERT INTO `fp_test` (`id`, `author_id`, `title`, `description`, `length`, `version`, `average_score`, `test_photo`, `start_message`, `completion_message`, `created_at`, `updated_at`) VALUES
(1, 18, 'Test', 'Test', '00:00:00', 0.00, 0.00, '', 'Test', 'Test', '2016-04-27 02:30:33', '2016-05-17 05:13:41'),
(2, 6, 'Test 2', 'Test 2', '00:00:00', 0.00, 0.00, '', 'Test 2', 'Test 2', '2016-04-27 02:32:35', '2016-04-27 02:32:35'),
(3, 6, 'Test 3', 'Test 3', '00:00:00', 0.00, 0.00, '', 'Test 3', 'Test 3', '2016-04-27 02:41:32', '2016-04-27 02:41:32'),
(4, 6, 'Test 4', 'Test 4', '00:00:00', 0.00, 0.00, '4.png', 'Test 4', 'Test 4', '2016-04-27 02:58:27', '2016-04-27 02:58:27'),
(5, 6, 'Test 5', 'Test 5', '00:00:00', 0.00, 0.00, '5.jpg', 'Test 5', 'Test 5', '2016-04-27 03:06:05', '2016-04-27 03:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `fp_test_feedback`
--

CREATE TABLE IF NOT EXISTS `fp_test_feedback` (
`id` int(10) unsigned NOT NULL,
  `test_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_test_question_tag`
--

CREATE TABLE IF NOT EXISTS `fp_test_question_tag` (
`id` int(10) unsigned NOT NULL,
  `tag` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_test_tag`
--

CREATE TABLE IF NOT EXISTS `fp_test_tag` (
`id` int(10) unsigned NOT NULL,
  `tag_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticket`
--

CREATE TABLE IF NOT EXISTS `fp_ticket` (
`ticket_id` int(11) NOT NULL,
  `ticket_subject` varchar(500) NOT NULL,
  `ticket_description` text,
  `ticket_priority` enum('low','medium','high','critical') NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  `ticket_status` enum('open','close') NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` tinytext NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_ticket`
--

INSERT INTO `fp_ticket` (`ticket_id`, `ticket_subject`, `ticket_description`, `ticket_priority`, `file`, `ticket_status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'test12', 'trest test', 'low', NULL, 'open', 0, '2016-03-16 13:58:41', '2016-03-16 05:58:41'),
(2, 'error for roles', 'please fixed it asap.', 'high', NULL, 'open', 0, '2016-03-17 08:22:07', '2016-03-17 00:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit` (
`id` int(10) unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `html` longtext COLLATE utf8_unicode_ci,
  `status_id` int(10) unsigned NOT NULL,
  `priority_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `agent_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_audits`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_audits` (
`id` int(10) unsigned NOT NULL,
  `operation` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `ticket_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_categories`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_categories` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_categories_users`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_categories_users` (
  `category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_comments`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_comments` (
`id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `html` longtext COLLATE utf8_unicode_ci,
  `user_id` int(10) unsigned NOT NULL,
  `ticket_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_priorities`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_priorities` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_settings`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_settings` (
`id` int(10) unsigned NOT NULL,
  `lang` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `default` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_ticketit_statuses`
--

CREATE TABLE IF NOT EXISTS `fp_ticketit_statuses` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fp_timer`
--

CREATE TABLE IF NOT EXISTS `fp_timer` (
`timer_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `project_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fp_timezone`
--

CREATE TABLE IF NOT EXISTS `fp_timezone` (
`timezone_id` int(11) NOT NULL,
  `timezone_name` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=420 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_timezone`
--

INSERT INTO `fp_timezone` (`timezone_id`, `timezone_name`) VALUES
(1, 'Africa/Abidjan'),
(2, 'Africa/Accra'),
(3, 'Africa/Addis_Ababa'),
(4, 'Africa/Algiers'),
(5, 'Africa/Asmara'),
(6, 'Africa/Asmera'),
(7, 'Africa/Bamako'),
(8, 'Africa/Bangui'),
(9, 'Africa/Banjul'),
(10, 'Africa/Bissau'),
(11, 'Africa/Blantyre'),
(12, 'Africa/Brazzaville'),
(13, 'Africa/Bujumbura'),
(14, 'Africa/Cairo'),
(15, 'Africa/Casablanca'),
(16, 'Africa/Ceuta'),
(17, 'Africa/Conakry'),
(18, 'Africa/Dakar'),
(19, 'Africa/Dar_es_Salaam'),
(20, 'Africa/Djibouti'),
(21, 'Africa/Douala'),
(22, 'Africa/El_Aaiun'),
(23, 'Africa/Freetown'),
(24, 'Africa/Gaborone'),
(25, 'Africa/Harare'),
(26, 'Africa/Johannesburg'),
(27, 'Africa/Juba'),
(28, 'Africa/Kampala'),
(29, 'Africa/Khartoum'),
(30, 'Africa/Kigali'),
(31, 'Africa/Kinshasa'),
(32, 'Africa/Lagos'),
(33, 'Africa/Libreville'),
(34, 'Africa/Lome'),
(35, 'Africa/Luanda'),
(36, 'Africa/Lubumbashi'),
(37, 'Africa/Lusaka'),
(38, 'Africa/Malabo'),
(39, 'Africa/Maputo'),
(40, 'Africa/Maseru'),
(41, 'Africa/Mbabane'),
(42, 'Africa/Mogadishu'),
(43, 'Africa/Monrovia'),
(44, 'Africa/Nairobi'),
(45, 'Africa/Ndjamena'),
(46, 'Africa/Niamey'),
(47, 'Africa/Nouakchott'),
(48, 'Africa/Ouagadougou'),
(49, 'Africa/Porto-Novo'),
(50, 'Africa/Sao_Tome'),
(51, 'Africa/Timbuktu'),
(52, 'Africa/Tripoli'),
(53, 'Africa/Tunis'),
(54, 'Africa/Windhoek'),
(55, 'America/Adak'),
(56, 'America/Anchorage'),
(57, 'America/Anguilla'),
(58, 'America/Antigua'),
(59, 'America/Araguaina'),
(60, 'America/Argentina/Buenos_Aires'),
(61, 'America/Argentina/Catamarca'),
(62, 'America/Argentina/ComodRivadavia'),
(63, 'America/Argentina/Cordoba'),
(64, 'America/Argentina/Jujuy'),
(65, 'America/Argentina/La_Rioja'),
(66, 'America/Argentina/Mendoza'),
(67, 'America/Argentina/Rio_Gallegos'),
(68, 'America/Argentina/Salta'),
(69, 'America/Argentina/San_Juan'),
(70, 'America/Argentina/San_Luis'),
(71, 'America/Argentina/Tucuman'),
(72, 'America/Argentina/Ushuaia'),
(73, 'America/Aruba'),
(74, 'America/Asuncion'),
(75, 'America/Atikokan'),
(76, 'America/Atka'),
(77, 'America/Bahia'),
(78, 'America/Bahia_Banderas'),
(79, 'America/Barbados'),
(80, 'America/Belem'),
(81, 'America/Belize'),
(82, 'America/Blanc-Sablon'),
(83, 'America/Boa_Vista'),
(84, 'America/Bogota'),
(85, 'America/Boise'),
(86, 'America/Buenos_Aires'),
(87, 'America/Cambridge_Bay'),
(88, 'America/Campo_Grande'),
(89, 'America/Cancun'),
(90, 'America/Caracas'),
(91, 'America/Catamarca'),
(92, 'America/Cayenne'),
(93, 'America/Cayman'),
(94, 'America/Chicago'),
(95, 'America/Chihuahua'),
(96, 'America/Coral_Harbour'),
(97, 'America/Cordoba'),
(98, 'America/Costa_Rica'),
(99, 'America/Creston'),
(100, 'America/Cuiaba'),
(101, 'America/Curacao'),
(102, 'America/Danmarkshavn'),
(103, 'America/Dawson'),
(104, 'America/Dawson_Creek'),
(105, 'America/Denver'),
(106, 'America/Detroit'),
(107, 'America/Dominica'),
(108, 'America/Edmonton'),
(109, 'America/Eirunepe'),
(110, 'America/El_Salvador'),
(111, 'America/Ensenada'),
(112, 'America/Fort_Wayne'),
(113, 'America/Fortaleza'),
(114, 'America/Glace_Bay'),
(115, 'America/Godthab'),
(116, 'America/Goose_Bay'),
(117, 'America/Grand_Turk'),
(118, 'America/Grenada'),
(119, 'America/Guadeloupe'),
(120, 'America/Guatemala'),
(121, 'America/Guayaquil'),
(122, 'America/Guyana'),
(123, 'America/Halifax'),
(124, 'America/Havana'),
(125, 'America/Hermosillo'),
(126, 'America/Indiana/Indianapolis'),
(127, 'America/Indiana/Knox'),
(128, 'America/Indiana/Marengo'),
(129, 'America/Indiana/Petersburg'),
(130, 'America/Indiana/Tell_City'),
(131, 'America/Indiana/Vevay'),
(132, 'America/Indiana/Vincennes'),
(133, 'America/Indiana/Winamac'),
(134, 'America/Indianapolis'),
(135, 'America/Inuvik'),
(136, 'America/Iqaluit'),
(137, 'America/Jamaica'),
(138, 'America/Jujuy'),
(139, 'America/Juneau'),
(140, 'America/Kentucky/Louisville'),
(141, 'America/Kentucky/Monticello'),
(142, 'America/Knox_IN'),
(143, 'America/Kralendijk'),
(144, 'America/La_Paz'),
(145, 'America/Lima'),
(146, 'America/Los_Angeles'),
(147, 'America/Louisville'),
(148, 'America/Lower_Princes'),
(149, 'America/Maceio'),
(150, 'America/Managua'),
(151, 'America/Manaus'),
(152, 'America/Marigot'),
(153, 'America/Martinique'),
(154, 'America/Matamoros'),
(155, 'America/Mazatlan'),
(156, 'America/Mendoza'),
(157, 'America/Menominee'),
(158, 'America/Merida'),
(159, 'America/Metlakatla'),
(160, 'America/Mexico_City'),
(161, 'America/Miquelon'),
(162, 'America/Moncton'),
(163, 'America/Monterrey'),
(164, 'America/Montevideo'),
(165, 'America/Montreal'),
(166, 'America/Montserrat'),
(167, 'America/Nassau'),
(168, 'America/New_York'),
(169, 'America/Nipigon'),
(170, 'America/Nome'),
(171, 'America/Noronha'),
(172, 'America/North_Dakota/Beulah'),
(173, 'America/North_Dakota/Center'),
(174, 'America/North_Dakota/New_Salem'),
(175, 'America/Ojinaga'),
(176, 'America/Panama'),
(177, 'America/Pangnirtung'),
(178, 'America/Paramaribo'),
(179, 'America/Phoenix'),
(180, 'America/Port_of_Spain'),
(181, 'America/Port-au-Prince'),
(182, 'America/Porto_Acre'),
(183, 'America/Porto_Velho'),
(184, 'America/Puerto_Rico'),
(185, 'America/Rainy_River'),
(186, 'America/Rankin_Inlet'),
(187, 'America/Recife'),
(188, 'America/Regina'),
(189, 'America/Resolute'),
(190, 'America/Rio_Branco'),
(191, 'America/Rosario'),
(192, 'America/Santa_Isabel'),
(193, 'America/Santarem'),
(194, 'America/Santiago'),
(195, 'America/Santo_Domingo'),
(196, 'America/Sao_Paulo'),
(197, 'America/Scoresbysund'),
(198, 'America/Shiprock'),
(199, 'America/Sitka'),
(200, 'America/St_Barthelemy'),
(201, 'America/St_Johns'),
(202, 'America/St_Kitts'),
(203, 'America/St_Lucia'),
(204, 'America/St_Thomas'),
(205, 'America/St_Vincent'),
(206, 'America/Swift_Current'),
(207, 'America/Tegucigalpa'),
(208, 'America/Thule'),
(209, 'America/Thunder_Bay'),
(210, 'America/Tijuana'),
(211, 'America/Toronto'),
(212, 'America/Tortola'),
(213, 'America/Vancouver'),
(214, 'America/Virgin'),
(215, 'America/Whitehorse'),
(216, 'America/Winnipeg'),
(217, 'America/Yakutat'),
(218, 'America/Yellowknife'),
(219, 'Antarctica/Casey'),
(220, 'Antarctica/Davis'),
(221, 'Antarctica/DumontDUrville'),
(222, 'Antarctica/Macquarie'),
(223, 'Antarctica/Mawson'),
(224, 'Antarctica/McMurdo'),
(225, 'Antarctica/Palmer'),
(226, 'Antarctica/Rothera'),
(227, 'Antarctica/South_Pole'),
(228, 'Antarctica/Syowa'),
(229, 'Antarctica/Vostok'),
(230, 'Arctic/Longyearbyen'),
(231, 'Asia/Aden'),
(232, 'Asia/Amman'),
(233, 'Asia/Anadyr'),
(234, 'Asia/Aqtau'),
(235, 'Asia/Aqtobe'),
(236, 'Asia/Ashkhabad'),
(237, 'Asia/Baghdad'),
(238, 'Asia/Bahrain'),
(239, 'Asia/Baku'),
(240, 'Asia/Beirut'),
(241, 'Asia/Bishkek'),
(242, 'Asia/Brunei'),
(243, 'Asia/Calcutta'),
(244, 'Asia/Chongqing'),
(245, 'Asia/Chungking'),
(246, 'Asia/Colombo'),
(247, 'Asia/Dacca'),
(248, 'Asia/Dhaka'),
(249, 'Asia/Dili'),
(250, 'Asia/Dubai'),
(251, 'Asia/Dushanbe'),
(252, 'Asia/Harbin'),
(253, 'Asia/Hebron'),
(254, 'Asia/Ho_Chi_Minh'),
(255, 'Asia/Hong_Kong'),
(256, 'Asia/Irkutsk'),
(257, 'Asia/Istanbul'),
(258, 'Asia/Jakarta'),
(259, 'Asia/Jayapura'),
(260, 'Asia/Kabul'),
(261, 'Asia/Kamchatka'),
(262, 'Asia/Karachi'),
(263, 'Asia/Kashgar'),
(264, 'Asia/Katmandu'),
(265, 'Asia/Khandyga'),
(266, 'Asia/Kolkata'),
(267, 'Asia/Krasnoyarsk'),
(268, 'Asia/Kuching'),
(269, 'Asia/Kuwait'),
(270, 'Asia/Macao'),
(271, 'Asia/Macau'),
(272, 'Asia/Makassar'),
(273, 'Asia/Manila'),
(274, 'Asia/Muscat'),
(275, 'Asia/Nicosia'),
(276, 'Asia/Novosibirsk'),
(277, 'Asia/Omsk'),
(278, 'Asia/Oral'),
(279, 'Asia/Phnom_Penh'),
(280, 'Asia/Pyongyang'),
(281, 'Asia/Qatar'),
(282, 'Asia/Qyzylorda'),
(283, 'Asia/Rangoon'),
(284, 'Asia/Saigon'),
(285, 'Asia/Sakhalin'),
(286, 'Asia/Samarkand'),
(287, 'Asia/Seoul'),
(288, 'Asia/Singapore'),
(289, 'Asia/Taipei'),
(290, 'Asia/Tashkent'),
(291, 'Asia/Tbilisi'),
(292, 'Asia/Tel_Aviv'),
(293, 'Asia/Thimbu'),
(294, 'Asia/Thimphu'),
(295, 'Asia/Tokyo'),
(296, 'Asia/Ulaanbaatar'),
(297, 'Asia/Ulan_Bator'),
(298, 'Asia/Urumqi'),
(299, 'Asia/Ust-Nera'),
(300, 'Asia/Vladivostok'),
(301, 'Asia/Yakutsk'),
(302, 'Asia/Yekaterinburg'),
(303, 'Asia/Yerevan'),
(304, 'Atlantic/Azores'),
(305, 'Atlantic/Canary'),
(306, 'Atlantic/Cape_Verde'),
(307, 'Atlantic/Faeroe'),
(308, 'Atlantic/Faroe'),
(309, 'Atlantic/Madeira'),
(310, 'Atlantic/Reykjavik'),
(311, 'Atlantic/South_Georgia'),
(312, 'Atlantic/St_Helena'),
(313, 'Australia/ACT'),
(314, 'Australia/Brisbane'),
(315, 'Australia/Broken_Hill'),
(316, 'Australia/Canberra'),
(317, 'Australia/Currie'),
(318, 'Australia/Eucla'),
(319, 'Australia/Hobart'),
(320, 'Australia/LHI'),
(321, 'Australia/Lindeman'),
(322, 'Australia/Melbourne'),
(323, 'Australia/North'),
(324, 'Australia/NSW'),
(325, 'Australia/Perth'),
(326, 'Australia/South'),
(327, 'Australia/Sydney'),
(328, 'Australia/Tasmania'),
(329, 'Australia/Victoria'),
(330, 'Australia/Yancowinna'),
(331, 'Europe/Amsterdam'),
(332, 'Europe/Athens'),
(333, 'Europe/Belfast'),
(334, 'Europe/Belgrade'),
(335, 'Europe/Berlin'),
(336, 'Europe/Brussels'),
(337, 'Europe/Bucharest'),
(338, 'Europe/Budapest'),
(339, 'Europe/Busingen'),
(340, 'Europe/Copenhagen'),
(341, 'Europe/Dublin'),
(342, 'Europe/Gibraltar'),
(343, 'Europe/Guernsey'),
(344, 'Europe/Isle_of_Man'),
(345, 'Europe/Istanbul'),
(346, 'Europe/Jersey'),
(347, 'Europe/Kaliningrad'),
(348, 'Europe/Lisbon'),
(349, 'Europe/Ljubljana'),
(350, 'Europe/London'),
(351, 'Europe/Luxembourg'),
(352, 'Europe/Malta'),
(353, 'Europe/Mariehamn'),
(354, 'Europe/Minsk'),
(355, 'Europe/Monaco'),
(356, 'Europe/Nicosia'),
(357, 'Europe/Oslo'),
(358, 'Europe/Paris'),
(359, 'Europe/Podgorica'),
(360, 'Europe/Riga'),
(361, 'Europe/Rome'),
(362, 'Europe/Samara'),
(363, 'Europe/San_Marino'),
(364, 'Europe/Simferopol'),
(365, 'Europe/Skopje'),
(366, 'Europe/Sofia'),
(367, 'Europe/Stockholm'),
(368, 'Europe/Tirane'),
(369, 'Europe/Tiraspol'),
(370, 'Europe/Uzhgorod'),
(371, 'Europe/Vaduz'),
(372, 'Europe/Vienna'),
(373, 'Europe/Vilnius'),
(374, 'Europe/Volgograd'),
(375, 'Europe/Warsaw'),
(376, 'Europe/Zaporozhye'),
(377, 'Europe/Zurich'),
(378, 'Indian/Antananarivo'),
(379, 'Indian/Christmas'),
(380, 'Indian/Cocos'),
(381, 'Indian/Comoro'),
(382, 'Indian/Kerguelen'),
(383, 'Indian/Maldives'),
(384, 'Indian/Mauritius'),
(385, 'Indian/Mayotte'),
(386, 'Indian/Reunion'),
(387, 'Pacific/Apia'),
(388, 'Pacific/Chatham'),
(389, 'Pacific/Chuuk'),
(390, 'Pacific/Easter'),
(391, 'Pacific/Efate'),
(392, 'Pacific/Fakaofo'),
(393, 'Pacific/Fiji'),
(394, 'Pacific/Funafuti'),
(395, 'Pacific/Galapagos'),
(396, 'Pacific/Guadalcanal'),
(397, 'Pacific/Guam'),
(398, 'Pacific/Honolulu'),
(399, 'Pacific/Johnston'),
(400, 'Pacific/Kosrae'),
(401, 'Pacific/Kwajalein'),
(402, 'Pacific/Majuro'),
(403, 'Pacific/Marquesas'),
(404, 'Pacific/Nauru'),
(405, 'Pacific/Niue'),
(406, 'Pacific/Norfolk'),
(407, 'Pacific/Noumea'),
(408, 'Pacific/Palau'),
(409, 'Pacific/Pitcairn'),
(410, 'Pacific/Pohnpei'),
(411, 'Pacific/Ponape'),
(412, 'Pacific/Rarotonga'),
(413, 'Pacific/Saipan'),
(414, 'Pacific/Samoa'),
(415, 'Pacific/Tahiti'),
(416, 'Pacific/Tongatapu'),
(417, 'Pacific/Truk'),
(418, 'Pacific/Wake'),
(419, 'Pacific/Wallis');

-- --------------------------------------------------------

--
-- Table structure for table `fp_user`
--

CREATE TABLE IF NOT EXISTS `fp_user` (
`user_id` int(10) unsigned NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ticketit_admin` tinyint(1) NOT NULL DEFAULT '0',
  `ticketit_agent` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fp_user`
--

INSERT INTO `fp_user` (`user_id`, `password`, `name`, `email`, `phone`, `photo`, `address_1`, `address_2`, `zipcode`, `country_id`, `skype`, `facebook`, `linkedin`, `user_status`, `remember_token`, `created_at`, `updated_at`, `ticketit_admin`, `ticketit_agent`) VALUES
(18, '$2y$10$UEexUYG9axwYUWw9U/tsf.w7XnGSSx1qjMuwyVon8zhNfj3ghltaC', 'Jexter Dean Buenaventura', 'projectmanager@hdenergy.ca', '1234567890', '', '', NULL, '', 0, NULL, NULL, NULL, 'Active', 'ny1YfYNSWiXbSBMIFULoVDNNKlDywsHQIZtEEDyB61tLBFDMI593fodJjO6R', '2016-05-17 13:10:04', '2016-05-17 05:10:04', 0, 0),
(41, '$2y$10$SyDLRHDaFq1ytX4wxitHp.KuD26I9oud4Ef0V6cK9G973Q6Qq.Ocm', 'John Doe', 'johndoe@gmail.com', '1234567890', '', 'Test', 'Test', '2600', 15, 'johndoe', 'johndoe', 'johndoe', 'Active', 'kvidrHq2eEGsuJLZfdKpuiauNs6hTc1AyKPJ6cDBZrXIoJtssCgyCUcamZHl', '2016-05-17 10:25:32', '2016-05-17 02:25:32', 0, 0),
(42, '$2y$10$Gs8o/ybd7TEuJx07Jp0cHeCDsJxbtqgId5sI3I73co39q2G1/I5TW', 'Jane Doe', 'janedoe@gmail.com', '1234567890', '', 'Test', 'Test', '2600', 15, 'janedoe', 'janedoe', 'janedoe', 'Active', '1cuFTwo8gO2nZ8kQnWuUJQmkvETtCb6aMGUwFUKKlNLvStiKyiqm2tKQoAg9', '2016-05-17 13:03:05', '2016-05-17 05:03:05', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fp_user_payroll_setting`
--

CREATE TABLE IF NOT EXISTS `fp_user_payroll_setting` (
`id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `hourly_rate` double(8,2) NOT NULL,
  `paypal_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pay_period_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fp_accounts`
--
ALTER TABLE `fp_accounts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_assigned_roles`
--
ALTER TABLE `fp_assigned_roles`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_assigned_user`
--
ALTER TABLE `fp_assigned_user`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_attachment`
--
ALTER TABLE `fp_attachment`
 ADD PRIMARY KEY (`attachment_id`);

--
-- Indexes for table `fp_billing`
--
ALTER TABLE `fp_billing`
 ADD PRIMARY KEY (`billing_id`);

--
-- Indexes for table `fp_bug`
--
ALTER TABLE `fp_bug`
 ADD PRIMARY KEY (`bug_id`), ADD KEY `bug_id` (`bug_id`);

--
-- Indexes for table `fp_client`
--
ALTER TABLE `fp_client`
 ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `fp_comment`
--
ALTER TABLE `fp_comment`
 ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `fp_companies`
--
ALTER TABLE `fp_companies`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_company_divisions`
--
ALTER TABLE `fp_company_divisions`
 ADD PRIMARY KEY (`id`), ADD KEY `company_divisions_company_id_foreign` (`company_id`);

--
-- Indexes for table `fp_country`
--
ALTER TABLE `fp_country`
 ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `fp_events`
--
ALTER TABLE `fp_events`
 ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `fp_item`
--
ALTER TABLE `fp_item`
 ADD PRIMARY KEY (`item_id`), ADD UNIQUE KEY `item_id` (`item_id`);

--
-- Indexes for table `fp_links`
--
ALTER TABLE `fp_links`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_link_categories`
--
ALTER TABLE `fp_link_categories`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `link_categories_slug_unique` (`slug`), ADD UNIQUE KEY `link_categories_name_unique` (`name`);

--
-- Indexes for table `fp_link_tags`
--
ALTER TABLE `fp_link_tags`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `link_tags_name_unique` (`name`);

--
-- Indexes for table `fp_meeting`
--
ALTER TABLE `fp_meeting`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_meeting_priority`
--
ALTER TABLE `fp_meeting_priority`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_meeting_type`
--
ALTER TABLE `fp_meeting_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_message`
--
ALTER TABLE `fp_message`
 ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `fp_newnote`
--
ALTER TABLE `fp_newnote`
 ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `fp_notes`
--
ALTER TABLE `fp_notes`
 ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `fp_password_resets`
--
ALTER TABLE `fp_password_resets`
 ADD KEY `password_resets_email_index` (`email`), ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `fp_payment`
--
ALTER TABLE `fp_payment`
 ADD PRIMARY KEY (`payment_id`), ADD UNIQUE KEY `payment_id` (`payment_id`);

--
-- Indexes for table `fp_payment_method`
--
ALTER TABLE `fp_payment_method`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_pay_period`
--
ALTER TABLE `fp_pay_period`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_permissions`
--
ALTER TABLE `fp_permissions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `fp_permission_role`
--
ALTER TABLE `fp_permission_role`
 ADD PRIMARY KEY (`id`), ADD KEY `permission_role_permission_id_index` (`permission_id`), ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indexes for table `fp_permission_user`
--
ALTER TABLE `fp_permission_user`
 ADD PRIMARY KEY (`id`), ADD KEY `permission_user_permission_id_index` (`permission_id`), ADD KEY `permission_user_user_id_index` (`user_id`);

--
-- Indexes for table `fp_profiles`
--
ALTER TABLE `fp_profiles`
 ADD PRIMARY KEY (`id`), ADD KEY `profiles_user_id_foreign` (`user_id`), ADD KEY `profiles_company_id_foreign` (`company_id`), ADD KEY `profiles_role_id_foreign` (`role_id`);

--
-- Indexes for table `fp_project`
--
ALTER TABLE `fp_project`
 ADD PRIMARY KEY (`project_id`), ADD KEY `project_user_id_foreign` (`user_id`);

--
-- Indexes for table `fp_question`
--
ALTER TABLE `fp_question`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_question_feedback`
--
ALTER TABLE `fp_question_feedback`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_question_tag`
--
ALTER TABLE `fp_question_tag`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_question_type`
--
ALTER TABLE `fp_question_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_roles`
--
ALTER TABLE `fp_roles`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `fp_role_user`
--
ALTER TABLE `fp_role_user`
 ADD PRIMARY KEY (`id`), ADD KEY `role_user_role_id_index` (`role_id`), ADD KEY `role_user_user_id_index` (`user_id`);

--
-- Indexes for table `fp_setting`
--
ALTER TABLE `fp_setting`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_task`
--
ALTER TABLE `fp_task`
 ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `fp_task_check_list`
--
ALTER TABLE `fp_task_check_list`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_task_check_list_order`
--
ALTER TABLE `fp_task_check_list_order`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_task_comment`
--
ALTER TABLE `fp_task_comment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_task_timer`
--
ALTER TABLE `fp_task_timer`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_team`
--
ALTER TABLE `fp_team`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_team_member`
--
ALTER TABLE `fp_team_member`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_team_project`
--
ALTER TABLE `fp_team_project`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_template`
--
ALTER TABLE `fp_template`
 ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `fp_test`
--
ALTER TABLE `fp_test`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_test_feedback`
--
ALTER TABLE `fp_test_feedback`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_test_question_tag`
--
ALTER TABLE `fp_test_question_tag`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_test_tag`
--
ALTER TABLE `fp_test_tag`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_ticket`
--
ALTER TABLE `fp_ticket`
 ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `fp_ticketit`
--
ALTER TABLE `fp_ticketit`
 ADD PRIMARY KEY (`id`), ADD KEY `ticketit_subject_index` (`subject`), ADD KEY `ticketit_status_id_index` (`status_id`), ADD KEY `ticketit_priority_id_index` (`priority_id`), ADD KEY `ticketit_user_id_index` (`user_id`), ADD KEY `ticketit_agent_id_index` (`agent_id`), ADD KEY `ticketit_category_id_index` (`category_id`), ADD KEY `ticketit_completed_at_index` (`completed_at`);

--
-- Indexes for table `fp_ticketit_audits`
--
ALTER TABLE `fp_ticketit_audits`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_ticketit_categories`
--
ALTER TABLE `fp_ticketit_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_ticketit_comments`
--
ALTER TABLE `fp_ticketit_comments`
 ADD PRIMARY KEY (`id`), ADD KEY `ticketit_comments_user_id_index` (`user_id`), ADD KEY `ticketit_comments_ticket_id_index` (`ticket_id`);

--
-- Indexes for table `fp_ticketit_priorities`
--
ALTER TABLE `fp_ticketit_priorities`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_ticketit_settings`
--
ALTER TABLE `fp_ticketit_settings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `ticketit_settings_slug_unique` (`slug`), ADD UNIQUE KEY `ticketit_settings_lang_unique` (`lang`), ADD KEY `ticketit_settings_lang_index` (`lang`), ADD KEY `ticketit_settings_slug_index` (`slug`);

--
-- Indexes for table `fp_ticketit_statuses`
--
ALTER TABLE `fp_ticketit_statuses`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_timer`
--
ALTER TABLE `fp_timer`
 ADD PRIMARY KEY (`timer_id`);

--
-- Indexes for table `fp_timezone`
--
ALTER TABLE `fp_timezone`
 ADD PRIMARY KEY (`timezone_id`);

--
-- Indexes for table `fp_user`
--
ALTER TABLE `fp_user`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `fp_user_payroll_setting`
--
ALTER TABLE `fp_user_payroll_setting`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fp_accounts`
--
ALTER TABLE `fp_accounts`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_assigned_roles`
--
ALTER TABLE `fp_assigned_roles`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_assigned_user`
--
ALTER TABLE `fp_assigned_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fp_attachment`
--
ALTER TABLE `fp_attachment`
MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_billing`
--
ALTER TABLE `fp_billing`
MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fp_bug`
--
ALTER TABLE `fp_bug`
MODIFY `bug_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fp_client`
--
ALTER TABLE `fp_client`
MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fp_comment`
--
ALTER TABLE `fp_comment`
MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fp_companies`
--
ALTER TABLE `fp_companies`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fp_company_divisions`
--
ALTER TABLE `fp_company_divisions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fp_country`
--
ALTER TABLE `fp_country`
MODIFY `country_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=258;
--
-- AUTO_INCREMENT for table `fp_events`
--
ALTER TABLE `fp_events`
MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fp_item`
--
ALTER TABLE `fp_item`
MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fp_links`
--
ALTER TABLE `fp_links`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fp_link_categories`
--
ALTER TABLE `fp_link_categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_link_tags`
--
ALTER TABLE `fp_link_tags`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_meeting`
--
ALTER TABLE `fp_meeting`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `fp_meeting_priority`
--
ALTER TABLE `fp_meeting_priority`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fp_meeting_type`
--
ALTER TABLE `fp_meeting_type`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fp_message`
--
ALTER TABLE `fp_message`
MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fp_newnote`
--
ALTER TABLE `fp_newnote`
MODIFY `note_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_notes`
--
ALTER TABLE `fp_notes`
MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `fp_payment`
--
ALTER TABLE `fp_payment`
MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_payment_method`
--
ALTER TABLE `fp_payment_method`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_pay_period`
--
ALTER TABLE `fp_pay_period`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_permissions`
--
ALTER TABLE `fp_permissions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_permission_role`
--
ALTER TABLE `fp_permission_role`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_permission_user`
--
ALTER TABLE `fp_permission_user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_profiles`
--
ALTER TABLE `fp_profiles`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `fp_project`
--
ALTER TABLE `fp_project`
MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `fp_question`
--
ALTER TABLE `fp_question`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fp_question_feedback`
--
ALTER TABLE `fp_question_feedback`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_question_tag`
--
ALTER TABLE `fp_question_tag`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_question_type`
--
ALTER TABLE `fp_question_type`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fp_roles`
--
ALTER TABLE `fp_roles`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fp_role_user`
--
ALTER TABLE `fp_role_user`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `fp_setting`
--
ALTER TABLE `fp_setting`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `fp_task`
--
ALTER TABLE `fp_task`
MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `fp_task_check_list`
--
ALTER TABLE `fp_task_check_list`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=279;
--
-- AUTO_INCREMENT for table `fp_task_check_list_order`
--
ALTER TABLE `fp_task_check_list_order`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=298;
--
-- AUTO_INCREMENT for table `fp_task_comment`
--
ALTER TABLE `fp_task_comment`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_task_timer`
--
ALTER TABLE `fp_task_timer`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `fp_team`
--
ALTER TABLE `fp_team`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `fp_team_member`
--
ALTER TABLE `fp_team_member`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `fp_team_project`
--
ALTER TABLE `fp_team_project`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `fp_template`
--
ALTER TABLE `fp_template`
MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_test`
--
ALTER TABLE `fp_test`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `fp_test_feedback`
--
ALTER TABLE `fp_test_feedback`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_test_question_tag`
--
ALTER TABLE `fp_test_question_tag`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_test_tag`
--
ALTER TABLE `fp_test_tag`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticket`
--
ALTER TABLE `fp_ticket`
MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `fp_ticketit`
--
ALTER TABLE `fp_ticketit`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticketit_audits`
--
ALTER TABLE `fp_ticketit_audits`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticketit_categories`
--
ALTER TABLE `fp_ticketit_categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticketit_comments`
--
ALTER TABLE `fp_ticketit_comments`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticketit_priorities`
--
ALTER TABLE `fp_ticketit_priorities`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticketit_settings`
--
ALTER TABLE `fp_ticketit_settings`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_ticketit_statuses`
--
ALTER TABLE `fp_ticketit_statuses`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_timer`
--
ALTER TABLE `fp_timer`
MODIFY `timer_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fp_timezone`
--
ALTER TABLE `fp_timezone`
MODIFY `timezone_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=420;
--
-- AUTO_INCREMENT for table `fp_user`
--
ALTER TABLE `fp_user`
MODIFY `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `fp_user_payroll_setting`
--
ALTER TABLE `fp_user_payroll_setting`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `fp_company_divisions`
--
ALTER TABLE `fp_company_divisions`
ADD CONSTRAINT `company_divisions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `fp_companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fp_permission_role`
--
ALTER TABLE `fp_permission_role`
ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `fp_permissions` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `fp_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fp_permission_user`
--
ALTER TABLE `fp_permission_user`
ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `fp_permissions` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `fp_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fp_profiles`
--
ALTER TABLE `fp_profiles`
ADD CONSTRAINT `profiles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `fp_companies` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `profiles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `fp_roles` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `fp_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fp_project`
--
ALTER TABLE `fp_project`
ADD CONSTRAINT `project_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `fp_user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fp_role_user`
--
ALTER TABLE `fp_role_user`
ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `fp_roles` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `fp_user` (`user_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
