-- Adminer 4.7.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `block_user`;
CREATE TABLE `block_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `block_user_id` int(11) DEFAULT NULL,
  `block_status` int(1) DEFAULT '1' COMMENT '0->unblocked, 1->blocked',
  `blockedDate` date DEFAULT NULL,
  `unblockedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `comment_ID` int(11) NOT NULL AUTO_INCREMENT,
  `comment_post_ID` int(11) DEFAULT NULL,
  `comment_count` bigint(20) DEFAULT NULL,
  `comment_user_id` int(11) DEFAULT NULL,
  `comment_user_IP` varchar(100) DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  `comment_content` text,
  `comment_approved` tinyint(4) DEFAULT NULL,
  `comment_like_count` int(10) DEFAULT NULL,
  `comment_author_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_user_id` (`comment_user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`comment_post_ID`) REFERENCES `posts` (`post_id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`comment_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `continent_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `email_template`;
CREATE TABLE `email_template` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `e_created_at` datetime DEFAULT NULL,
  `e_updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `email_template` (`eid`, `title`, `subject`, `content`, `e_created_at`, `e_updated_at`) VALUES
(1,	'Register Verification',	'Email Verification OTP',	'<title></title>\r\n<div class=\"notification\" id=\"mailsub\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"min-width: 320px;\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td bgcolor=\"#eff3f8\">\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table_width_100\" style=\"max-width: 680px; min-width: 300px;\" width=\"100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><!-- padding -->\r\n						<div style=\"height: 80px; line-height: 80px; font-size: 10px;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header -->\r\n					<tr>\r\n						<td bgcolor=\"#ffffff\">\r\n						<div style=\" float: left;  width: 100%;height: 5px;background-color: #3cb6ff;\">&nbsp;</div>\r\n						<!-- padding -->\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #f1f1f1;\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td>&nbsp;</td>\r\n									<td align=\"left\" valign=\"middle\"><!-- padding -->\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n\r\n									<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td align=\"left\" class=\"mob_center\" valign=\"top\"><a color=\"#596167\" href=\"{site_url}\" size=\"3\" style=\"color: #596167;font-family: Arial, Helvetica, sans-serif;font-size: 13px;text-align: center;display: block;\" target=\"_blank\"><img alt=\"take_away\" src=\"{site_url}/resources/assets/img/logo.png\" style=\"width: 30%; border-width: 0px; border-style: solid;\" /></a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						<!-- padding -->\r\n\r\n						<div style=\"height: 5px; line-height: 50px; font-size: 10px; border-top: 1px solid #ddd;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header END--><!--content 1 -->\r\n					<tr>\r\n						<td bgcolor=\"#fff\">\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 20px;color: #292929;font-weight: 600;\">Welcome {user_name},</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"line-height: 44px;margin-left: 15px;\">\r\n									<p><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #292929;font-weight: 600;\">Your registered email-id is {user_email}<br />\r\n									Your registered Mobile is {user_mobile}<br />\r\n									OTP is&nbsp;{user_otp}<!--</font--></span></font></p>\r\n									</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">With Regards,</span></font></div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">Your Social Networking Team&nbsp;</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\">\r\n									<td><!-- padding --><!-- padding -->\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\" style=\"background-color:  #3cb6ff;\">\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;     margin: 0px 15px;\r\n							background-color:  #3cb6ff;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;    margin: 0px 15px;\r\n								background-color:  #3cb6ff;\">\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy;This email was sent to {user_email} from Social Networking.</font></p>\r\n\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy;Social Networking&nbsp;2019-20. All rights reserved. </font></p>\r\n									</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;    margin: 0px 15px;\r\n							background-color:  #3cb6ff;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td>\r\n									<div style=\"\r\n								border-top: 1px solid #000;\r\n								margin: 0px 15px;\r\n							\">&nbsp;</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>',	'2018-09-04 14:52:25',	'2020-01-22 11:52:47'),
(3,	'Reset Password',	'Reset Password',	'<title></title>\r\n<div class=\"notification\" id=\"mailsub\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"min-width: 320px;\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td bgcolor=\"#eff3f8\">\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table_width_100\" style=\"max-width: 680px; min-width: 300px;\" width=\"100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><!-- padding -->\r\n						<div style=\"height: 80px; line-height: 80px; font-size: 10px;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header -->\r\n					<tr>\r\n						<td bgcolor=\"#ffffff\">\r\n						<div style=\" float: left;  width: 100%;height: 5px;background-color: #3cb6ff;\">&nbsp;</div>\r\n						<!-- padding -->\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #3cb6ff;\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td>&nbsp;</td>\r\n									<td align=\"left\" valign=\"middle\"><!-- padding -->\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n\r\n									<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td align=\"left\" class=\"mob_center\" valign=\"top\"><a color=\"#596167\" href=\"#\" size=\"3\" style=\"color: #596167;font-family: Arial, Helvetica, sans-serif;font-size: 13px;text-align: center;display: block;\" target=\"_blank\"><img alt=\"Confess Book\" src=\"{site_url}/resources/assets/images/logo.jpg\" style=\"width: 30%; border-width: 0px; border-style: solid;\" /></a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						<!-- padding -->\r\n\r\n						<div style=\"height: 5px; line-height: 50px; font-size: 10px; border-top: 1px solid #ddd;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header END--><!--content 1 -->\r\n					<tr>\r\n						<td bgcolor=\"#fbfcfd\">\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><font size=\"5\"><font style=\"font-size: 34px;\"><span style=\"font-size:16px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><span style=\"color:#29292\"><span style=\"font-weight:600\">Hi {user_name},</span></span></span></span></font></font></font></font></div>\r\n\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><font size=\"5\"><font style=\"font-size: 34px;\"><span style=\"font-size:16px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><span style=\"color:#29292\"><span style=\"font-weight:600\">Please click on the below link to reset your password:</span></span></span></span></font></font></font></font></div>\r\n\r\n									<div style=\"line-height: 44px;margin-left: 15px;\">\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929;\"><a href=\"{reset_url}\" style=\"color: #0185bd;\">Reset Password</a></span></font></div>\r\n									</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">With Regards,</span></font></div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">your Confess Book Team&nbsp;</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\">\r\n									<td><!-- padding --><!-- padding -->\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\" style=\"background-color:  #3cb6ff;\">\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;     margin: 0px 15px;\r\n    background-color:  #3cb6ff;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;    margin: 0px 15px;\r\n    background-color:  #3cb6ff;\">\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy; This email was sent to {user_email} from Confess Book.</font></p>\r\n\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy; ( </font><font color=\"#ffffff\"><font face=\"Arial, Helvetica, sans-serif\"><font size=\"4\"><font style=\"font-size: 15px;\">Confess Book</font></font></font></font><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"> ) 2019-20. All rights reserved. </font></p>\r\n									</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;    margin: 0px 15px;\r\n    background-color:  #3cb6ff;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td>\r\n									<div style=\"\r\n    border-top: 1px solid #000;\r\n    margin: 0px 15px;\r\n\">&nbsp;</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<!--content 1 END-->\r\n	</tbody>\r\n</table>\r\n</div>',	'2018-09-05 15:24:51',	'2019-05-27 00:49:25'),
(4,	'Forgot Password OTP',	'Forgot Password OTP',	'<title></title>\r\n<div class=\"notification\" id=\"mailsub\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"min-width: 320px;\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td bgcolor=\"#eff3f8\">\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table_width_100\" style=\"max-width: 680px; min-width: 300px;\" width=\"100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><!-- padding -->\r\n						<div style=\"height: 80px; line-height: 80px; font-size: 10px;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header -->\r\n					<tr>\r\n						<td bgcolor=\"#ffffff\">\r\n						<div style=\" float: left;  width: 100%;height: 5px;background-color: #3cb6ff;\">&nbsp;</div>\r\n						<!-- padding -->\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #f1f1f1;\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td>&nbsp;</td>\r\n									<td align=\"left\" valign=\"middle\"><!-- padding -->\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n\r\n									<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td align=\"left\" class=\"mob_center\" valign=\"top\"><a color=\"#596167\" href=\"{site_url}\" size=\"3\" style=\"color: #596167;font-family: Arial, Helvetica, sans-serif;font-size: 13px;text-align: center;display: block;\" target=\"_blank\"><img alt=\"take_away\" src=\"{site_url}/resources/assets/img/logo.png\" style=\"width: 30%; border-width: 0px; border-style: solid;\" /></a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						<!-- padding -->\r\n\r\n						<div style=\"height: 5px; line-height: 50px; font-size: 10px; border-top: 1px solid #ddd;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header END--><!--content 1 -->\r\n					<tr>\r\n						<td bgcolor=\"#fff\">\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 20px;color: #292929;font-weight: 600;\">Hi&nbsp;{user_name},</span></font></div>\r\n\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:16px\">&nbsp;</span></font></font><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:16px\">Your forgot Password&nbsp;</span></font></font><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:16px\">OTP is<b>&nbsp;{user_otp}</b></span></font></font><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #292929;font-weight: 600;\"><!--</font--></span></font></div>\r\n									<!-- padding --></td>\r\n								</tr>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">With Regards,</span></font></div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">Your Social Networking  Team&nbsp;</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\">\r\n									<td><!-- padding --><!-- padding -->\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\" style=\"background-color:  #3cb6ff;\">\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;     margin: 0px 15px;\r\n							background-color:  #3cb6ff;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;    margin: 0px 15px;\r\n								background-color:  #3cb6ff;\">\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy;This email was sent to {user_email} from Social Networking.</font></p>\r\n\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy;Social Networking&nbsp;2019-20. All rights reserved. </font></p>\r\n									</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;    margin: 0px 15px;\r\n							background-color:  #3cb6ff;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td>\r\n									<div style=\"\r\n								border-top: 1px solid #000;\r\n								margin: 0px 15px;\r\n							\">&nbsp;</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>',	'2019-04-05 13:29:44',	'2020-01-22 07:52:40'),
(5,	'Social Login Info',	'Login Information',	'<title></title>\r\n<div class=\"notification\" id=\"mailsub\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"min-width: 320px;\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td bgcolor=\"#eff3f8\">\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table_width_100\" style=\"max-width: 680px; min-width: 300px;\" width=\"100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><!-- padding -->\r\n						<div style=\"height: 80px; line-height: 80px; font-size: 10px;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header -->\r\n					<tr>\r\n						<td bgcolor=\"#ffffff\">\r\n						<div style=\" float: left;  width: 100%;height: 5px;background-color: #3cb6ff;\">&nbsp;</div>\r\n						<!-- padding -->\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #3cb6ff;\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td>&nbsp;</td>\r\n									<td align=\"left\" valign=\"middle\"><!-- padding -->\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n\r\n									<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td align=\"left\" class=\"mob_center\" valign=\"top\"><a color=\"#596167\" href=\"#\" size=\"3\" style=\"color: #596167;font-family: Arial, Helvetica, sans-serif;font-size: 13px;text-align: center;display: block;\" target=\"_blank\"><img alt=\"Confess Book\" src=\"{site_url}/resources/assets/images/logo.jpg\" style=\"width: 30%; border-width: 0px; border-style: solid;\" /></a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						<!-- padding -->\r\n\r\n						<div style=\"height: 5px; line-height: 50px; font-size: 10px; border-top: 1px solid #ddd;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header END--><!--content 1 -->\r\n					<tr>\r\n						<td bgcolor=\"#fff\">\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 20px;color: #292929;font-weight: 600;\">Welcome to the </span></font><span style=\"line-height:44px\"><span style=\"line-height:44px\"><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><font size=\"5\"><font style=\"font-size:34px\"><span style=\"font-size:20px\"><span style=\"font-family:Arial, Helvetica, sans-serif\"><span style=\"color:#292929\"><span style=\"font-weight:600\">TAMS</span></span></span></span></font></font></font></font></span></span><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 20px;color: #292929;font-weight: 600;\"> {unique_name}</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"line-height: 44px;margin-left: 15px;\">\r\n									<p><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #292929;font-weight: 600;\">Your registered email-id is {user_email}, </span></font></p>\r\n\r\n									<p><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #292929;font-weight: 600;\">Your Password: {user_password}</span></font></p>\r\n									</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">With Regards,</span></font></div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">your Confess Book Team&nbsp;</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\">\r\n									<td><!-- padding --><!-- padding -->\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\" style=\"background-color:  #3cb6ff;\">\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;     margin: 0px 15px;\r\n    background-color:  #3cb6ff;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;    margin: 0px 15px;\r\n    background-color:  #3cb6ff;\">\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy; This email was sent to {user_email} from Confess Book.</font></p>\r\n\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy; ( Confess Book ) 2019-20. All rights reserved. </font></p>\r\n									</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;    margin: 0px 15px;\r\n    background-color:  #3cb6ff;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td>\r\n									<div style=\"\r\n    border-top: 1px solid #000;\r\n    margin: 0px 15px;\r\n\">&nbsp;</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<!--content 1 END-->\r\n	</tbody>\r\n</table>\r\n</div>',	'2019-04-05 13:29:44',	'2019-09-06 06:58:14'),
(6,	'Resend otp',	'Your Resend OTP',	'<title></title>\r\n<div class=\"notification\" id=\"mailsub\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"min-width: 320px;\" width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td bgcolor=\"#eff3f8\">\r\n			<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"table_width_100\" style=\"max-width: 680px; min-width: 300px;\" width=\"100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><!-- padding -->\r\n						<div style=\"height: 80px; line-height: 80px; font-size: 10px;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header -->\r\n					<tr>\r\n						<td bgcolor=\"#ffffff\">\r\n						<div style=\" float: left;  width: 100%;height: 5px;background-color: #3cb6ff;\">&nbsp;</div>\r\n						<!-- padding -->\r\n\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #f1f1f1;\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td>&nbsp;</td>\r\n									<td align=\"left\" valign=\"middle\"><!-- padding -->\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n\r\n									<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td align=\"left\" class=\"mob_center\" valign=\"top\"><a color=\"#596167\" href=\"{site_url}\" size=\"3\" style=\"color: #596167;font-family: Arial, Helvetica, sans-serif;font-size: 13px;text-align: center;display: block;\" target=\"_blank\"><img alt=\"take_away\" src=\"{site_url}/resources/assets/img/logo.png\" style=\"width: 30%; border-width: 0px; border-style: solid;\" /></a></td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n\r\n									<div style=\"height: 20px; line-height: 20px; font-size: 10px; \">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						<!-- padding -->\r\n\r\n						<div style=\"height: 5px; line-height: 50px; font-size: 10px; border-top: 1px solid #ddd;\">&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<!--header END--><!--content 1 -->\r\n					<tr>\r\n						<td bgcolor=\"#fff\">\r\n						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n							<tbody>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 20px;color: #292929;font-weight: 600;\">Hi {user_name},</span></font></div>\r\n\r\n									<div style=\"line-height: 44px;margin-left: 15px;\"><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:16px\">&nbsp;</span></font></font><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:16px\">Your resend&nbsp;</span></font></font><font color=\"#292929\"><font face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:16px\">OTP is</span></font></font><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #292929;font-weight: 600;\">&nbsp; {user_otp} </span></font><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"5\" style=\"font-size: 34px;\"><span style=\"font-family: Arial, Helvetica, sans-serif;font-size: 16px;color: #292929;font-weight: 600;\"><!--</font--></span></font></div>\r\n									<!-- padding --></td>\r\n								</tr>\r\n								<tr>\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">With Regards,</span></font></div>\r\n\r\n									<div style=\"line-height: 24px;\"><font color=\"#292929\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\"><span style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #292929; margin-left:15px;\">Your Social Networking Team&nbsp;</span></font></div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\">\r\n									<td><!-- padding --><!-- padding -->\r\n									<div style=\"height: 15px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr align=\"center\" style=\"background-color:  #3cb6ff;\">\r\n									<td><!-- padding -->\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;     margin: 0px 15px;\r\n							background-color:  #3cb6ff;\">&nbsp;</div>\r\n\r\n									<div style=\"line-height: 24px;    margin: 0px 15px;\r\n								background-color:  #3cb6ff;\">\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy;This email was sent to {user_email} from Social Networking.</font></p>\r\n\r\n									<p style=\"font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #ffffff; margin-left:15px; text-align:center;    font-weight: 600;\"><font color=\"#ffffff\" face=\"Arial, Helvetica, sans-serif\" size=\"4\" style=\"font-size: 15px;\">&copy;Social Networking&nbsp;2019-20. All rights reserved. </font></p>\r\n									</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;    margin: 0px 15px;\r\n							background-color:  #3cb6ff;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n								<tr>\r\n									<td>\r\n									<div style=\"\r\n								border-top: 1px solid #000;\r\n								margin: 0px 15px;\r\n							\">&nbsp;</div>\r\n									<!-- padding -->\r\n\r\n									<div style=\"height: 10px; line-height: 40px; font-size: 10px;\">&nbsp;</div>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</div>',	NULL,	'2020-01-22 11:41:50');

DROP TABLE IF EXISTS `FamilyTreeNodes`;
CREATE TABLE `FamilyTreeNodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `familyTreeParentsId` int(11) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `Marriages` int(11) DEFAULT NULL,
  `husbandname` varchar(255) DEFAULT NULL,
  `wifename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `familyTreeParentsId` (`familyTreeParentsId`),
  CONSTRAINT `FamilyTreeNodes_ibfk_1` FOREIGN KEY (`familyTreeParentsId`) REFERENCES `FamilyTreeParents` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `FamilyTreeParents`;
CREATE TABLE `FamilyTreeParents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mother_id` int(11) DEFAULT NULL,
  `father_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `follower`;
CREATE TABLE `follower` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `follower_id` int(11) DEFAULT NULL,
  `request_status` int(1) NOT NULL DEFAULT '0' COMMENT '0->pending, 1->accepted, 2->declined',
  `followedDate` date DEFAULT NULL,
  `unfollewedDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `follower` (`id`, `user_id`, `follower_id`, `request_status`, `followedDate`, `unfollewedDate`) VALUES
(3,	14,	15,	1,	'2020-01-24',	NULL);

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `friend_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `friends` (`id`, `user_id`, `friend_id`) VALUES
(3,	14,	15);

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) DEFAULT NULL,
  `group_owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `group_users`;
CREATE TABLE `group_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_group_admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `group_users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  CONSTRAINT `group_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `likes_counter`;
CREATE TABLE `likes_counter` (
  `like_ID` int(11) NOT NULL AUTO_INCREMENT,
  `like_type` varchar(10) DEFAULT NULL,
  `like_content_ID` int(11) DEFAULT NULL,
  `like_IP` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`like_ID`),
  KEY `like_content_ID` (`like_content_ID`),
  CONSTRAINT `likes_counter_ibfk_1` FOREIGN KEY (`like_content_ID`) REFERENCES `posts` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `message_status` int(11) DEFAULT NULL,
  `message_text` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `send_by` int(11) DEFAULT NULL,
  `send_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `notifications` (`id`, `type`, `type_id`, `status`, `send_by`, `send_to`, `created_at`, `updated_at`) VALUES
(1,	'post',	7,	'1',	14,	'15',	'0000-00-00 00:00:00',	NULL),
(2,	'post',	9,	'1',	14,	'15',	'0000-00-00 00:00:00',	NULL),
(3,	'post',	16,	'1',	14,	'15',	'0000-00-00 00:00:00',	NULL),
(4,	'post',	17,	'1',	14,	'15',	'0000-00-00 00:00:00',	NULL);

DROP TABLE IF EXISTS `notifications_status`;
CREATE TABLE `notifications_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `seen_status` int(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `notifications_status` (`id`, `notification_id`, `user_id`, `seen_status`, `created_at`, `updated_at`) VALUES
(1,	1,	15,	NULL,	NULL,	NULL),
(2,	2,	15,	NULL,	NULL,	NULL),
(3,	3,	15,	NULL,	NULL,	NULL),
(4,	4,	15,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `post_date` datetime DEFAULT NULL,
  `post_content` longtext,
  `post_media_json` text,
  `post_status_id` int(11) DEFAULT NULL,
  `post_type` varchar(10) DEFAULT NULL,
  `post_like_count` int(10) DEFAULT NULL,
  `post_comment_count` int(11) DEFAULT NULL,
  `post_has_article` tinyint(4) DEFAULT NULL,
  `article_title` text,
  `article_content` longtext,
  PRIMARY KEY (`post_id`),
  KEY `post_status_id` (`post_status_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_status_id`) REFERENCES `post_status` (`status_id`),
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `posts` (`post_id`, `user_id`, `post_date`, `post_content`, `post_media_json`, `post_status_id`, `post_type`, `post_like_count`, `post_comment_count`, `post_has_article`, `article_title`, `article_content`) VALUES
(1,	14,	'2020-01-28 10:51:32',	'This is my first post',	NULL,	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(2,	14,	'2020-01-29 09:25:46',	'sssss',	NULL,	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(3,	14,	'2020-01-29 09:38:38',	'this is demo txt',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580290628_SamplePNGImage_100kbmb.png\"},{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580290628_SampleJPGImage_50kbmb.jpg\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580290628_3mb1.mp4\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580290628_small.mp4\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(4,	15,	'2020-01-29 09:42:05',	'this is demo txt 15',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580290865_SamplePNGImage_100kbmb.png\"},{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580290865_SampleJPGImage_50kbmb.jpg\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580290865_3mb1.mp4\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580290865_small.mp4\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(5,	14,	'2020-02-04 10:06:26',	'This is demo feed',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580810786_download.jpeg\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(6,	14,	'2020-02-04 10:28:17',	'This is pravin\'s demo text for 18',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580812097_361900-coastal-road-x-xd.jpg\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(7,	14,	'2020-02-04 10:29:20',	'This is pravin\'s demo text for 18',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580812160_361900-coastal-road-x-xd.jpg\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(8,	17,	'2020-02-04 10:49:19',	'This is demo feed',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580813359_download.jpeg\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(9,	14,	'2020-02-04 12:07:18',	'This is pravin\'s demo text for 18',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580818038_361900-coastal-road-x-xd.jpg\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580818038_0956t-whpql.3gp\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(10,	17,	'2020-02-04 12:07:47',	'This is demo feed jlfks',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580818067_download.jpeg\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580818067_0956t-whpql.3gp\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(11,	17,	'2020-02-04 12:28:40',	'This is pravin\'s demo text for 18',	'[{\"type\":\"image\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580819320_361900-coastal-road-x-xd.jpg\"},{\"type\":\"video\",\"url\":\"http:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580819320_0956t-whpql.3gp\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(12,	17,	'2020-02-04 12:48:39',	'This is pravin\'s demo text for 18',	'[{\"type\":\"image\",\"url\":\"https:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580820519_361900-coastal-road-x-xd.jpg\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(13,	17,	'2020-02-04 12:59:55',	'avi video upload',	'[{\"type\":\"image\",\"url\":\"https:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_images\\/1580821195_361900-coastal-road-x-xd.jpg\"},{\"type\":\"video\",\"url\":\"https:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580821195_0956t-whpql.avi\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(15,	17,	'2020-02-05 07:10:32',	'Mp4 Video',	'[{\"type\":\"video\",\"url\":\"https:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580886632_toystory.mp4\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(16,	14,	'2020-02-05 07:17:44',	'3gp video upload',	'[{\"type\":\"video\",\"url\":\"https:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580887064_0956t-whpql.3gp\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL),
(17,	14,	'2020-02-05 09:49:49',	'3gp video upload',	'[{\"type\":\"video\",\"url\":\"https:\\/\\/votivelaravel.in\\/socialnetworking\\/public\\/uploads\\/feed_videos\\/1580896189_0956t-whpql.avi\"}]',	1,	NULL,	0,	0,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `post_media`;
CREATE TABLE `post_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `media_type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `post_media` (`id`, `post_id`, `media_type`, `media_url`, `created_at`, `updated_at`) VALUES
(1,	1,	'image',	'1580208692_SamplePNGImage_100kbmb.png',	'2020-01-28 10:51:32',	NULL),
(2,	1,	'image',	'1580208692_SampleJPGImage_50kbmb.jpg',	'2020-01-28 10:51:32',	NULL),
(3,	1,	'video',	'1580208692_3mb4.mp4',	'2020-01-28 10:51:32',	NULL),
(4,	1,	'video',	'1580208692_3mb3.mp4',	'2020-01-28 10:51:32',	NULL),
(5,	2,	'image',	'1580289946_SamplePNGImage_100kbmb.png',	'2020-01-29 09:25:46',	NULL),
(6,	2,	'image',	'1580289946_SampleJPGImage_50kbmb.jpg',	'2020-01-29 09:25:46',	NULL),
(7,	2,	'video',	'1580289946_3mb1.mp4',	'2020-01-29 09:25:46',	NULL),
(8,	2,	'video',	'1580289946_small.mp4',	'2020-01-29 09:25:46',	NULL),
(9,	3,	'image',	'1580290628_SamplePNGImage_100kbmb.png',	'2020-01-29 09:37:08',	NULL),
(10,	3,	'image',	'1580290628_SampleJPGImage_50kbmb.jpg',	'2020-01-29 09:37:08',	NULL),
(11,	3,	'video',	'1580290628_3mb1.mp4',	'2020-01-29 09:37:08',	NULL),
(12,	3,	'video',	'1580290628_small.mp4',	'2020-01-29 09:37:08',	NULL),
(13,	4,	'image',	'1580290865_SamplePNGImage_100kbmb.png',	'2020-01-29 09:41:05',	NULL),
(14,	4,	'image',	'1580290865_SampleJPGImage_50kbmb.jpg',	'2020-01-29 09:41:05',	NULL),
(15,	4,	'video',	'1580290865_3mb1.mp4',	'2020-01-29 09:41:05',	NULL),
(16,	4,	'video',	'1580290865_small.mp4',	'2020-01-29 09:41:05',	NULL),
(17,	5,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580810786_download.jpeg',	'2020-02-04 10:06:26',	NULL),
(18,	6,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580812097_361900-coastal-road-x-xd.jpg',	'2020-02-04 10:28:17',	NULL),
(19,	7,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580812160_361900-coastal-road-x-xd.jpg',	'2020-02-04 10:29:20',	NULL),
(20,	8,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580813359_download.jpeg',	'2020-02-04 10:49:19',	NULL),
(21,	9,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580818038_361900-coastal-road-x-xd.jpg',	'2020-02-04 12:07:18',	NULL),
(22,	9,	'video',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_videos/1580818038_0956t-whpql.3gp',	'2020-02-04 12:07:18',	NULL),
(23,	10,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580818067_download.jpeg',	'2020-02-04 12:07:47',	NULL),
(24,	10,	'video',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_videos/1580818067_0956t-whpql.3gp',	'2020-02-04 12:07:47',	NULL),
(25,	11,	'image',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_images/1580819320_361900-coastal-road-x-xd.jpg',	'2020-02-04 12:28:40',	NULL),
(26,	11,	'video',	'http://votivelaravel.in/socialnetworking/public/uploads/feed_videos/1580819320_0956t-whpql.3gp',	'2020-02-04 12:28:41',	NULL),
(27,	12,	'image',	'1580820519_361900-coastal-road-x-xd.jpg',	'2020-02-04 12:48:39',	NULL),
(28,	13,	'image',	'1580821195_361900-coastal-road-x-xd.jpg',	'2020-02-04 12:59:55',	NULL),
(29,	13,	'video',	'1580821195_0956t-whpql.avi',	'2020-02-04 12:59:55',	NULL),
(31,	15,	'video',	'1580886632_toystory.mp4',	'2020-02-05 07:10:40',	NULL),
(32,	16,	'video',	'1580887064_0956t-whpql.3gp',	'2020-02-05 07:17:44',	NULL),
(33,	17,	'video',	'1580896189_0956t-whpql.avi',	'2020-02-05 09:49:49',	NULL);

DROP TABLE IF EXISTS `post_status`;
CREATE TABLE `post_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `post_status` (`status_id`, `status`) VALUES
(1,	'active'),
(2,	'inactive');

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) DEFAULT NULL,
  `permission_level` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(100) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(255) DEFAULT NULL,
  `oauth_id` varchar(255) DEFAULT NULL,
  `user_mob` varchar(255) DEFAULT NULL,
  `user_gender` varchar(255) NOT NULL,
  `user_address` text,
  `user_city` varchar(255) DEFAULT NULL,
  `works_at` varchar(255) DEFAULT NULL,
  `study_at` varchar(255) DEFAULT NULL,
  `relation_status` varchar(255) DEFAULT NULL,
  `languages_known` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `bio` text,
  `user_zipcode` varchar(255) DEFAULT NULL,
  `wallet_amount` varchar(255) DEFAULT NULL,
  `user_lat` varchar(255) DEFAULT NULL,
  `user_long` varchar(255) DEFAULT NULL,
  `country_code` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `user_status` int(11) DEFAULT NULL,
  `user_pass` varchar(255) DEFAULT NULL,
  `deviceid` varchar(255) DEFAULT NULL,
  `devicetype` varchar(255) DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `user_role` int(11) DEFAULT NULL,
  `user_fbid` varchar(255) DEFAULT NULL,
  `user_gpid` varchar(255) DEFAULT NULL,
  `restrictions` varchar(255) DEFAULT NULL,
  `user_otp` varchar(255) DEFAULT NULL,
  `forgot_pass_otp` int(11) DEFAULT NULL,
  `checkout_verificaiton` int(11) DEFAULT NULL,
  `log_id` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `is_private_dob` int(1) DEFAULT '0',
  `role_id` int(11) DEFAULT NULL,
  `email_verified` int(1) DEFAULT '0',
  `email_verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  KEY `role_id` (`role_id`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`country_code`) REFERENCES `countries` (`id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `users_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `token`, `username`, `fullname`, `email`, `password`, `profile_image`, `oauth_provider`, `oauth_id`, `user_mob`, `user_gender`, `user_address`, `user_city`, `works_at`, `study_at`, `relation_status`, `languages_known`, `cover_image`, `bio`, `user_zipcode`, `wallet_amount`, `user_lat`, `user_long`, `country_code`, `is_admin`, `remember_token`, `user_status`, `user_pass`, `deviceid`, `devicetype`, `api_token`, `user_role`, `user_fbid`, `user_gpid`, `restrictions`, `user_otp`, `forgot_pass_otp`, `checkout_verificaiton`, `log_id`, `dob`, `is_private_dob`, `role_id`, `email_verified`, `email_verified_at`, `created_at`, `updated_at`, `updated_by`) VALUES
(1,	NULL,	'admin',	'admin',	'akash@mailinator.com',	'$2y$10$72i/q9xh31ciVsPqT96CKuHTtiY5N9IWfnkdkN8ogc6g3hHZVIgXe',	NULL,	NULL,	NULL,	'1234567890',	'male',	'Indore',	'Indore',	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	1,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	1,	'2020-01-24 09:31:02',	'2020-02-03 10:52:07',	'2020-02-03 10:52:07',	NULL),
(14,	NULL,	'@lokesh',	'Lokesh Solanki',	'lokesh@yopmail.com',	'$2y$10$jcDqlxBbypGXSRsJzBUpYecNfCcLJZeLHxyGe74PigkuqOglaLuXu',	'1579863487.jpg',	NULL,	NULL,	'7878787878',	'male',	'Bhawarkua',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	NULL,	626505,	NULL,	NULL,	'2001-02-02',	0,	NULL,	1,	'2020-02-04 10:01:18',	'2020-02-05 09:51:15',	'2020-02-05 09:51:15',	NULL),
(15,	NULL,	'@pravin4',	'Pravin Solanki',	'pravin4@yopmail.com',	'$2y$10$mIurH1jP2M1T68Wtx6FA6OOA.2DFia.R5VjKAbFf2X2qz0PsNoAee',	'1579865837.png',	NULL,	NULL,	'1234567891',	'male',	'Bholaram1',	NULL,	'1',	'2',	'3',	'4',	'1579865837.png',	'5',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'349940',	727743,	NULL,	NULL,	'2001-10-11',	0,	NULL,	1,	'2020-01-24 11:07:28',	'2020-02-05 12:03:51',	'2020-02-05 12:03:51',	NULL),
(16,	NULL,	'@test',	'megha2',	'test@yopmail.com',	'$2y$10$dPxc8i7A3wYTJA3dsVmnK.pyQ.lu.CN0uMJrOswwNGWsMZVhVGSCe',	'1580378626.jpg',	'',	'',	'7878787878',	'male',	'Bhawarkua',	'',	'',	'',	'',	'',	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'675211',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-01-30 10:03:46',	'2020-01-30 10:03:46',	NULL),
(17,	NULL,	'@sanjay',	'sanjay bairagi',	'votiveiphone.sanjay@gmail.com',	'$2y$10$cJ7AmdBKED5VQfuVp4CZN.GI6JTuhKBD/RShIAEVLyAVcIqQBhweK',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	1,	'2020-02-04 10:44:48',	'2020-02-04 10:44:48',	'2020-02-04 10:44:48',	NULL),
(18,	NULL,	'@abhi',	'sanjay bairagi',	'abhi@gmail.com',	'$2y$10$OILlci2T1s9YYTeH3usShePrGE4AoODNU26O3IXYuKlBqXcGtHgkS',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'266646',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 07:52:04',	'2020-02-05 07:52:04',	NULL),
(19,	NULL,	'@amn',	'sanjay bairagi',	'amn@gmail.com',	'$2y$10$SriDtu8jlRdqjHWvYkqygOymm7KdwPwS5UhGriyo8KS83JmQQxBiK',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'513621',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 07:57:45',	'2020-02-05 07:57:45',	NULL),
(20,	NULL,	'Sumit Trivedi',	'sanjay bairagi',	'VotiveMobile.sumit@gmail.com',	'$2y$10$5saI6Jz0GcnABsI7jCap2esF5Pj8Lhdu.D/fyozdkK4A972TaPQz.',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'463859',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 09:17:24',	'2020-02-05 09:17:24',	NULL),
(21,	NULL,	'@sanjay1',	'sanjay bairagi',	'sanjay1@yopmail.com',	'$2y$10$irL4y58kJaQ3/bglJzG2E.WHfVzJK/j5CiPYDhq12C2oy/1HKaUJy',	'http://votivelaravel.in/socialnetworking/public/uploads/profile_image1580896268.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'203872',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 09:51:08',	'2020-02-05 09:51:08',	NULL),
(22,	NULL,	'@sanjay11',	'sanjay bairagi',	'sanjay11@yopmail.com',	'$2y$10$rSmWauZYQMmdALnmMSWpNO4h9i9mRPdIw0MDGdXYUG87LT7n4Uu1O',	'http://votivelaravel.in/socialnetworking/public/uploads/profile_image/1580896594.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'407211',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 09:56:35',	'2020-02-05 09:56:35',	NULL),
(23,	NULL,	'Sumit',	'Sumit',	'sumit@gmail.com',	'$2y$10$1OA3GGa7Lr6co2zvjjMrmui7vJx5eqr8XLTq7nFxXvqMtkCOo.sla',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7869814486',	'Male',	'Indore',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'569472',	NULL,	NULL,	NULL,	'1995-02-05',	0,	NULL,	1,	'2020-02-05 12:07:10',	'2020-02-05 12:08:51',	'2020-02-05 12:08:51',	NULL),
(24,	NULL,	'anuj',	'sanjay bairagi',	'anuj@gmail.com',	'$2y$10$iqYMrxeG6dqjkqVOovBT4eR8FOdStP23k1HTozhx0cU/.BsYr4uNW',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'118717',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 10:28:13',	'2020-02-05 10:28:13',	NULL),
(25,	NULL,	'Sumit_test',	'sanjay bairagi',	'sumit_test@yopmail.com',	'$2y$10$sIHopyl0vqlozTvzNlLI6ORuc07iJFdsTkYrrCnJEM6N/YJHnrJaW',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'582201',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	1,	'2020-02-05 12:15:23',	'2020-02-05 12:16:45',	'2020-02-05 12:16:45',	NULL),
(26,	NULL,	'renuka',	'renuka',	'renuka@yopmail.com',	'$2y$10$zCFdzuRUqHkHYWsUtWjg3e0rDXh3zp.dgaXssBiUuNE0sLQW3KsC.',	'http://votivelaravel.in/socialnetworking/public/uploads/profile_image/1580906248.png',	'',	'',	'77869814486',	'Female',	'Indore',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'1995-02-05',	0,	NULL,	1,	'2020-02-05 12:38:39',	'2020-02-05 12:38:39',	'2020-02-05 12:38:39',	NULL),
(27,	NULL,	'@sanjay111',	'sanjay bairagi',	'votiveiphone.sanjay111@gmail.com',	'$2y$10$Eode.jk4cV.bEv.9sdBIhewIygAjP4Jl5/GG1E9V.qle9QurlM0x2',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_user.jpg',	'',	'',	'7878787878',	'male',	'Rajendra nagar',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	'294730',	NULL,	NULL,	NULL,	'2001-02-02',	0,	NULL,	0,	NULL,	'2020-02-05 12:44:22',	'2020-02-05 12:44:22',	NULL),
(28,	NULL,	'aabha',	'aabha',	'abha@yopmail.com',	'$2y$10$dkvwzULB0ZxlfjxANdcCCuHI1U.31qQrZxHYp6fWV2MgWcdNztur2',	'http://votivelaravel.in/socialnetworking/public/uploads/profile_image/1580907335.png',	'',	'',	'7869814486',	'Female',	'Indore',	'',	'',	'',	'',	'',	'http://votivelaravel.in/socialnetworking/resources/assets/images/blank_image.jpg',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	NULL,	'',	'',	'',	2,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'1995-02-05',	0,	NULL,	1,	'2020-02-05 12:57:35',	'2020-02-05 12:57:35',	'2020-02-05 12:57:35',	NULL);

DROP TABLE IF EXISTS `user_otpdetail`;
CREATE TABLE `user_otpdetail` (
  `otp_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `otp_for` varchar(255) DEFAULT NULL,
  `user_mob` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `otp_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`otp_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_otpdetail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user_otpdetail` (`otp_id`, `user_id`, `otp_for`, `user_mob`, `user_email`, `otp_number`, `created_at`, `updated_at`) VALUES
(3,	14,	'registration',	'7878787878',	'pravin3@yopmail.com',	'929755',	'2020-01-24 10:58:07',	NULL),
(4,	15,	'registration',	'7878787878',	'pravin4@yopmail.com',	'374340',	'2020-01-24 11:02:19',	NULL),
(5,	15,	'forgotpassword',	'7878787878',	'pravin4@yopmail.com',	'664462',	'2020-01-24 11:10:35',	NULL),
(6,	16,	'registration',	'7878787878',	'test@yopmail.com',	'675211',	'2020-01-30 10:03:46',	NULL),
(7,	17,	'registration',	'7878787878',	'votiveiphone.sanjay@gmail.com',	'937734',	'2020-02-04 10:41:39',	NULL),
(8,	18,	'registration',	'7878787878',	'abhi@gmail.com',	'266646',	'2020-02-05 07:52:04',	NULL),
(9,	19,	'registration',	'7878787878',	'amn@gmail.com',	'513621',	'2020-02-05 07:57:45',	NULL),
(10,	20,	'registration',	'7878787878',	'VotiveMobile.sumit@gmail.com',	'463859',	'2020-02-05 09:17:24',	NULL),
(11,	15,	'forgotpassword',	'1234567891',	'pravin4@yopmail.com',	'727743',	'2020-02-05 09:39:07',	NULL),
(12,	21,	'registration',	'7878787878',	'sanjay1@yopmail.com',	'203872',	'2020-02-05 09:51:08',	NULL),
(13,	14,	'forgotpassword',	'7878787878',	'lokesh@yopmail.com',	'885100',	'2020-02-05 09:51:12',	NULL),
(14,	14,	'forgotpassword',	'7878787878',	'lokesh@yopmail.com',	'626505',	'2020-02-05 09:51:15',	NULL),
(15,	22,	'registration',	'7878787878',	'sanjay11@yopmail.com',	'407211',	'2020-02-05 09:56:35',	NULL),
(16,	23,	'registration',	'7869814486',	'sumit@gmail.com',	'579160',	'2020-02-05 10:17:53',	NULL),
(17,	24,	'registration',	'7878787878',	'anuj@gmail.com',	'118717',	'2020-02-05 10:28:13',	NULL),
(18,	25,	'registration',	'7878787878',	'sumit_test@yopmail.com',	'395489',	'2020-02-05 12:13:18',	NULL),
(19,	26,	'registration',	'77869814486',	'renuka@yopmail.com',	'347935',	'2020-02-05 12:37:28',	NULL),
(20,	27,	'registration',	'7878787878',	'votiveiphone.sanjay111@gmail.com',	'294730',	'2020-02-05 12:44:22',	NULL),
(21,	28,	'registration',	'7869814486',	'abha@yopmail.com',	'345124',	'2020-02-05 12:55:35',	NULL);

-- 2020-02-06 05:27:09
