-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Set 26, 2014 alle 00:30
-- Versione del server: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ponyexpress_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `agent`
--

CREATE TABLE IF NOT EXISTS `agent` (
`id` int(11) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `name` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `gcm_id` varchar(250) DEFAULT NULL,
  `status` enum('logged','unlogged','inactive') NOT NULL,
  `last_position_lat` double DEFAULT NULL,
  `last_position_lon` double DEFAULT NULL,
  `last_update` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dump dei dati per la tabella `agent`
--

INSERT INTO `agent` (`id`, `mail`, `password`, `name`, `phone`, `gcm_id`, `status`, `last_position_lat`, `last_position_lon`, `last_update`) VALUES
(1, 'sebas@gmail.com', 'c2d628ba98ed491776c9335e988e2e3b', 'federico arroyave', '3273774779', 'APA91bFsG7Jd62WX9rEAVwTBseOdl62qpDdhq8RmEbp2tegvHt6BR8tuCeD-D0dC6EVjHmp1yKh7hJc0GrGyTnHtRNVkC3qc6yoWROrNMDFL1Uvc26eMHaTZKtc6ycBs-5cU3DA55TzeWZ0pmoZkq49KzMXadsOR3XOaKOrMLGK-46JMjmtnC88', 'logged', 45.0668872, 7.6644217, '2013-11-16 12:34:26'),
(4, 'alf@ponyexpress.com', '9927c72ec10b32b2da58e67919078772', 'Alfonso', '3273774778', 'APA91bH2VDTy_iPYX5IFDeyN6hG5334YEjmni1d7tHLXlCBn6g9ffXSzVZuvl9qbydQUTImgEcEJL_1msiCSIK4azFImTUhvbWt9b-dW2sb3Fsvnb7gZDfwBo3PebAqDzoqpHj78-vCZeZaK-9oJfUNrXxd7zLTtzaft9sNFPlcozu57vkEs3SQ', 'inactive', 45.051499, 7.674659, '2014-05-21 18:28:49'),
(5, 'admin@ponyexpress.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', '3124567890', NULL, 'unlogged', NULL, NULL, '2014-06-02 14:50:20');

-- --------------------------------------------------------

--
-- Struttura della tabella `delivery`
--

CREATE TABLE IF NOT EXISTS `delivery` (
`id` int(11) NOT NULL,
  `tracking_code` varchar(45) NOT NULL,
  `delivery_code` varchar(45) NOT NULL,
  `sender_address` varchar(100) NOT NULL,
  `sender_info` varchar(145) DEFAULT NULL,
  `sender_email` varchar(100) NOT NULL,
  `recipient_address` varchar(100) NOT NULL,
  `recipient_info` varchar(145) DEFAULT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `state` enum('waiting','delivering','delivered') NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pickup_time` int(15) DEFAULT NULL,
  `delivery_time` int(15) DEFAULT NULL,
  `recip_sign` longtext,
  `agent_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Dump dei dati per la tabella `delivery`
--

INSERT INTO `delivery` (`id`, `tracking_code`, `delivery_code`, `sender_address`, `sender_info`, `sender_email`, `recipient_address`, `recipient_info`, `recipient_email`, `state`, `submission_time`, `pickup_time`, `delivery_time`, `recip_sign`, `agent_id`) VALUES
(45, 'b9bfaf2', '2a5034b', 'Corso Luigi Einaudi, 26', 'laura', 'lema@pere.za', 'Corso Vittorio Emanuele II, 123', 'fede', 'rico@arro.ya', 'delivered', '2014-09-22 09:43:30', 1411380454, 1411670851, '/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDABALDA4MChAODQ4SERATGCgaGBYWGDEjJR0oOjM9PDkz\nODdASFxOQERXRTc4UG1RV19iZ2hnPk1xeXBkeFxlZ2P/2wBDARESEhgVGC8aGi9jQjhCY2NjY2Nj\nY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2P/wAARCAIrBLADASIA\nAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQA\nAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3\nODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWm\np6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEA\nAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSEx\nBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElK\nU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3\nuLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD0Ciii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK5j4g309l4d/0eRo2mmWMspwQ\nMEnn/gNdPXH/ABO/5F63/wCvtf8A0B6AMbwd4ye2dbDVpi0DHEc7nJjPox9Pft9OnpAIIyDkGvNL\nXwmur+DbK9slC3yiTI6CYB24Pv6H8Po/wd4sfTpBpWrsywqdkcj8GE/3W9v5fToAek0UgIIyDkGl\noAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiig\nAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAC\niiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKK\nKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooo\noAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiig\nAooooAK4/wCJ3/IvW/8A19r/AOgPXYVx/wATv+Ret/8Ar7X/ANAegDQ8Cf8AIoWP/bT/ANGNWb47\n8MxXtnLqlqm27hXdIFH+tUdc+4Hf2x6VpeBP+RQsf+2n/oxq3yAQQRkHqDQByHw61n7bpbafM2Zr\nX7mTyYz0/I8flXYV5RdpL4N8ZCWJT9mLblH96Juq/h0+oFepwyx3EMc0TB45FDKw7g8g0ASUUUUA\nFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAU\nUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRR\nRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFF\nABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUA\nFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAV\nx/xO/wCRet/+vtf/AEB67CuP+J3/ACL1v/19r/6A9AGh4E/5FCx/7af+jGroK5/wJ/yKFj/20/8A\nRjV0FAHNeOtF/tXRWliXNza5kTHVh/EPy5/Cs34b619os30qZsyQfPFnuncfgf5+1dvXlcSLofxI\nWNAEj+07QB0CyDj8Bu/SgD1SiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiig\nAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAC\niiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKK\nKKACiiigAooooAKKKKACiiigArF1/wATWWgPCl3FcOZgSvlKpxjHXJHrW1WdquhabrDRtqFt5xiB\nCfOy4z16EelAGbpvjfRdQkMZme1bt9pAQH8ckfma6FHWRA6MGVhkMpyCK43Vfh3YXB36dM9o2PuN\nmRT+ZyPzNc99k8WeFifI8426nOYv3sR98dvxAoA9Vory8fEfWAAPs9kSBySjc/8Aj1H/AAsfWP8A\nn2sf++H/APiqAPUKK8v/AOFj6x/z7WP/AHw//wAVQPiPq+Rm2sSO+Ef/AOKoA9QorhrH4k2r4W+s\npYj/AHomDj8jj+tdDaeKtDu03R6lAnqJm8s/+PYoA2KKz/7d0f8A6Ctj/wCBCf40f27o/wD0FbH/\nAMCE/wAaANCis/8At3R/+grY/wDgQn+NTWupWN65S0vbe4dRkrFKrED14NAFqiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigArlfiNB5vhhnx/qZkf+a/8As1dVWP4ug+0eF9RTGcRF/wDvk7v6\nUAZ/w7n83wuiZ/1Mrp+u7/2auorhvhdNusL+D+5Kr/8AfQx/7LXc0AFeZfEm3a2162vY+PNiGD/t\nKf8AArXptcd8S7PztDhuQPmt5Rk+isMH9dtAHV2lwt3Zw3KfcmjVx9CM1NXOeArz7X4XtwTloGaJ\nvwOR+hFdHQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAU\nUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRR\nRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFF\nABRRRQAUUUUAFFFFADVVVztUDJycDrTqKKACmsqupV1DKeoIyDTqKAMS+8JaHfZMlhHG5/ih/dn8\nhx+lYFz8NLZpM2upSxJ6SRBz+YIruqKAPP8A/hWX/UX/APJb/wCzo/4Vl/1F/wDyW/8As69AooA8\n/wD+FZf9Rf8A8lv/ALOtnwv4R/4R69luPt32jzI9m3ytmOQc9T6V09FABRRRQAUUUUAFFFFABRRR\nQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFA\nBRRRQAUUUUAFFFFABRRRQAVn+IP+Re1P/r0l/wDQDWhWf4g/5F7U/wDr0l/9ANAHH/Cv/mKf9sv/\nAGevQK8/+Ff/ADFP+2X/ALPXoFABWb4is/t+gX1vjLNCxUf7Q5H6gVpUUAeffC68+e+sieoWVR+h\n/wDZa9Bryzw//wASX4gtan5YzK8H1U/d/wDZa9ToAKKKKACiiigAooooAKKKKACiiigAooooAKKK\nKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoooo\nAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigA\nooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACi\niigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKK\nKACiiigAooooAKKKKACiiigAooooAKKKKACiiigArL8TSeV4b1JvW3dfzGP61qVg+N5PL8JX5HdV\nX83AoAwPhZHiDUpOfmaNfyDf413tcX8MI8aJdScfNcFfyVf8a7SgAooooA8v1v8A5KhH/wBfdt/J\nK9Qry/W/+SoR/wDX3bfySvUKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACii\nigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKK\nACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA\nKKKKACiiigAooooAKKKKACiiigDnfEfiyDQLuK3ltZJjJHvBVgMckf0roI23xq4GNwBrzT4n/wDI\natf+vf8A9mavSLf/AI9ov9wfyoAlooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigA\nooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACi\niigAooooAKKKKACiiigArl/iJJs8LyLx88qL+uf6V1FcZ8TptuiW0OeXuA34BT/iKALXw5j2eGFb\nn55nb+Q/pXU1geB4vJ8J2II5YMx/Fif5YrfoAKKKKAPL9b/5KhH/ANfdt/JK9Qry/W/+SoR/9fdt\n/JK9QoAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nrB8WeIv+Eeso3SETTTMVQMcAY6k/pxQBvUVz3g3WrvWtMklvYisiSEBwhVXU9MfTp+VdDQB5l8T/\nAPkNWv8A17/+zNXpFv8A8e0X+4P5V5d4nnfxL4wSzs8EIRbow74JLN9Bz+Ar1RFCIqDooAoAdRRR\nQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFA\nBRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABXnnxSmzPp8A\nP3VdyPqQB/I16HXl/wATif8AhILcZ4+yr/6G9AHoWhw/Z9DsIccpboD9dozV6kUBVCgYAGAKWgAo\noooA8v1v/kqEf/X3bfySvUK8v1v/AJKhH/19238kr1CgAooooAKKKKACiiigAooooAKKKKACiiig\nAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAC\niiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKK\nKKACiiigAooooAKKKKACiiigAooooAKKKKACuJ+J1pNLptpcxqWjgkIkI/h3YwfzGPxrtqoazead\nZ2D/ANqyIltKNjBgTuz2AHJ/CgDB8JeJtLOg21vc3UVtNAoiZZXC5x0IJ7Vi+J/Fd7qWpDTNCkcR\nk+Xui+9K3sew/wA9K5zV4dEjLtpV5czEt8qPFhQP97Of0p/hzVz4e1FbuSyEwdMDdlWAPdTQB3Hg\nzwlLo0r3t+yG5ZdqIpz5YPXn1+n9a6+sTSvFekaoFEV0sUp/5ZTfI2fT0P4GtugAooooAKKKKACi\niigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKK\nKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAry/4nf8AIw2//Xov/ob1\n6hXl/wATv+Rht/8Ar0X/ANDegD1CiiigAooooA8v1v8A5KhH/wBfdt/JK9Qry3U2Fz8T0MPz4vIQ\ncf7IUN+WDXqVABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFF\nABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUA\nFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAU\nUUUAFeX+PdR/tfXYNOs280QHywF7yMcEfyH51r+O/E9zaXA0rTX2SMoM0ifeGeij0OOfxFSeC/CM\nmnyjUtTUfaSP3UXXy89z7/y/kAXrPwHokCJ50Mlw4AyXkIBP0GK2rvSNPvbNbS4tIngQbUXbjYP9\nkjp+FXaKAOD1P4bxNl9LvCh7RzjI/wC+hyPyNY6w+MfDv7uEXRhHTYPOjx7DnH6V6pRQB5jB8Q9X\nt22XdrbyY6gqUb+f9K2bL4j6fLgXlpPbn1Qh1/of0rsJ7eC4XbPDHKvo6hh+tYt74M0K8yTZiFj/\nABQsU/Tp+lAEX/Cd6B/z9Sf9+W/wo/4TvQP+fqT/AL8t/hVb/hXmi/37v/v4P8KP+FeaL/fu/wDv\n4P8ACgCz/wAJ3oH/AD9Sf9+W/wAK3LC9g1Gzju7Vi0MgJUkEZ5x0/Cua/wCFeaL/AH7v/v4P8K6P\nTbCHTLCKztyxiiBC7zk9c/1oAtUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUU\nUUAFFFFABRRRQAUVR1PV7DSYvMvrlIs9FPLN9AOTXEax8Q5pswaPbmPdwJpBlj9F6D8c0AehPLGh\nw7qp9CcUizRMcLIhJ7BhXl9p4L13WFa8u5FheQ5zcs29vcjBx+NOuvh/rFnCZ7eWGeRDkJExDfUZ\nAoA9Soryv/hJfFejbDeiby+ABdQcH/gWAT+dei6JqcesaVBexgDzF+Zc/dYdR+dAF+iiigAooooA\nKKKKACiiigAooooAKKKKACiiigAooooAKKKKACvLvGv+neOIrXrjyocfU5/9mr1GvLk/0/4nnvtu\nz/5DH/2NAHqNFFFABUN5cJaWc1zJ9yGNnb6AZqauK+I+tfZrFNLhb97cfNLg9EB6fif5GgDG+H1u\n+o+JbjUZvmMStIW/23OP5bq9OrmvAmkNpehrJKuJ7oiVgeoX+Eflz+NdLQAUUUUAFFFFABRRRQAU\nUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRR\nRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFF\nABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFZ+vX/8AZeiXd4CA0cZ2Z/vHhf1IrQri\n/idcSR6RawL9yWbLn6Dgfr+lAGN4C0U6tqMmrXrtILeQFd3PmSdck+3B/EV6bXP+BrVbXwta4OTN\nmVjjuT/gBXQUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUU\nAFFFFABRRRQAUUUUAFFFYfiXxLa6Bb/NiW6cfu4QevufQUAaOo6nZ6Xbma+uEhTtuPLfQdT+FcFq\n/j69vZfsuiQNEGO1XK7pG+g6D9aoaXpGqeM9Qe9vJmSDOGmI4H+yg/z/AI+h6N4f07RY8WcA8wjD\nSvy7fj2+goA4jTPAupapL9q1q4eAPyQx3yt9fT8fyrttJ8O6Xo4BtLVRIOsr/M5/E9PwxWrRQAUU\nUUAUdZ0uDWNNlsrjIV+VYdVYdCK4H4YXEw1e6thI3ktbmQp23BlAP5E16UzBFLMQFUZJPYV5j8Mv\n+Rhn4P8Ax6t+HzJQB6hRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUx5ETG91XPqcULLG5wk\nisfQHNAD6KKKACvL9E/5KhJ/193P8nr1CvL9E/5KhJ/193P8noA9QooooAhu7qKytJbm4bbFEpZj\n7CvL9DtpfF3i2S7u1zArebKOwUcKn8h9Aa2PiTrWFj0eBuWxJPj0/hX+v5V0Pg/Rv7G0SNJFxczf\nvJs9QT0X8B+uaANyloooAKKKKACiiigAooooAKw9f8U6foalJH865xxBGefxPaovGPiEaHpu2Eg3\nk+ViH90d2P0/nXJ+EfCH9sJ/aWqNJ5DNlEzgy+pJ9P50AQSa74n8TTNFYLKkWcbbYbFX6v8A4mll\n8P8AjGKJpDJdEIMkLdZP4AHmvTra2gtIFgtokiiQYVEGAKloA4bwN4pur27/ALK1Jg7hD5UrffYj\nqG9TjPPtXc15bqSLafE5Fg+QG8hJx/t7S357j+depUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAF\nFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUU\nUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABTXR\nHGHVWx6jNOooARQFACgADsKWiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACsfxTY\nXup6JLaWDxrJIy7t7EZUHOAfXIFbFFAHlY1Dxb4aOLgTmFf+ew82P/vrt+BFbFh8SYSuNQsZFYfx\nQEMD+Bxj8zXdnkYNZN54X0S+k3z6dFuPUplM/XaRmgDOh8f6FJ9+SeL/AH4j/TNXYfF2gzfc1KIf\n74Zf5gVRl+H+hyfdW4i/3Jf8QaozfDWxP+pv7lP99Vb+WKAOtt9SsLrH2a9t5s9klVv5GrVeb3Hw\n1u1z9m1GGT/rohT+Warf8Ir4s07/AI85XIH/AD73O39CRQB6jRXlv9p+NtO/1i3jIOpeASD/AL6w\nf51LB8RNVgfZeWlvJjqMMjfzP8qAPTaK4q0+JGnyYF1Z3EJPdCHA/kf0ratPFuhXeNmoxIfSXMeP\nzwKANuobq6t7KBp7qZIYl6s5wKdFPDNH5kUqSJ/eVgR+deTgah418RSRC5IiBZ03k7YowccD15H1\noA2td+ILyE22iRkZ4+0OvJ/3V/x/Kq+h+C7/AFS6F9rrSJGx3MjsfNk+v90fr/Ouu0Lwtp2iKHij\n865xzPIMn8PStygCOCGK3hSGBFjjQbVVRgAVJRRQAUUUUAFFFYXiHxTY6JC6mRZbzHyQKcnP+16C\ngCj471+PTdMewiO66ukK4B+4h4JP16D/AOtVf4baU9rp01/MhVrogR5HOwd/xJ/QVgeGNEuPFGrS\nanqZZ7YPmRj/AMtW7KPYcfhxXqKqFUKoAUDAA6CgBaKKKACiiigAooooAKKKKACiiigAooooAK5L\nxh4uTSEazsGV75hy3UQj1Pv7fn75vizxnOtzLpekBlkVjHJMPvFuhCj68Z/L1o8LeCH8xNQ1tctn\nctu3JJ9X/wAPz9KAMiy8J674hX7fdTBBIMq9y5LOPYc4H5VLN4A1uzUT2s8MsqHIEUhVh9CQP516\nhS0AeY6f4y1nRJ/susQPOq9VmG2QD2Pf8fzrvtH1my1q18+ykzjh0bhkPoRUmpaZZ6rbGC9gWVD0\nJHK+4PY1geGvBw0PVp7s3TSpjbCo4OD13ev+TQB1deX6J/yVCT/r7uf5PXqFeX6H/wAlQk/6+7n+\nT0AeoVS1bU7fSNPkvLpsIg4UdXbsB71ZnnitoHnndY4o1LMzdAK8s1S9vfG3iCO1s1K26EiJT0Re\n7t/n0H1AJvCFjN4i8TS6nejfHC/muT0L/wAK/QfyFeo1R0jS7bR9Pjs7VcKvLMert3Jq9QAUUUUA\nFFFFABRRRQAVV1LULfS7GW8un2xRjJx1J7Ae5q1XAfFC9+WysFPUmZx+i/8As1AGNYwXfjbxO09w\nCtupBkweI4x0UH1P+Jr1WKNIYkiiUJGgCqoHAA6Csrwrpkel6DaxKgWR0Ekp7liMnP06fhWxQAUU\nUUAeXa0Q3xQj2nP+l2/T6JXqNeWeC0OseM3vbjBZQ9yR7kgD8i36V6nQAUUUUAFFFFABRRRQAUUU\nUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQ\nAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAB\nRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFF\nFFABRRRQAVDcWtvdJsuYIpl9JEDD9amooAwbrwdoN1ktYLG3rExTH4Dj9Kxbv4bWb5NpfzRHsJFD\nj9MV3FFAHkmt+Er/AMP2zXhvIDECEBRmVyT2xj+vrVLQv7etQ95osNwQf3bPFD5nocdD7Vs+NNRl\n17xDFpVid6Qv5agHhpD1P0HT8DXoWkadFpWmQWUPKxLgn+8e5/E0Aed/8Jh4ptf+PiLP/XW22/yx\nUsXxJ1Ef66ytX/3Ny/1NemVFLbQT/wCugjk/30B/nQBwH/CzJv8AoGR/9/j/AIUf8LMm/wCgZH/3\n+P8AhXb/ANkab/0DrT/vyv8AhR/ZGm/9A60/78r/AIUAcR/wsyb/AKBkf/f4/wCFQ3HxJv3GLext\n4z6uzP8A4V3v9kab/wBA60/78r/hUsFjaWzbre1gib1SMKf0oA80bWfGOrKVgS6Ebf8APGDYP++s\nZH51oaH8PpJGFxrcpXPPkRtlj/vN/h+deh0UAQ2ttBZ26W9tEsUSDCoowBU1FFABRRRQAUUUUAFF\nFFABRRRQAUUUUAFY/iPxBbaBZeZJh53GIoQeWPqfQe9aF/dpYWFxdyfchjLkeuB0ry7RtNvPGetz\nXN7MREhBmcdgc4VR26H/AD1AL3w9sZ73XZtVnjJjjDHzCODIx7fgT+lel1DaWsFlbR21tGscMYwq\nr2qagAooooAKKKKAGSOsUTyOcKgLH6CvMvh8hvPFE95L/wAs43kJ/wBpjj+prv8AxCWHh7UihwRa\nyHP/AAE147ZXt1DbT2VpuBvCqvs+8wGflH1J/SgDpfFviGbxBfppOlbpLfeFGz/ls3/xI/8Ar12X\nhbw9FoNhsO17qUAzSD1/uj2FUvBvhZdGgF3dqGv5F5HXyh6D39T/AJPU0AFFFFABRRRQAUUUUAFF\nFFABXlvilft3xDS0mOYzLBDj0U7Sf/QjXqVeX63/AMlQj/6+7b+SUAen0tFFABXA+M/F5BfStJcl\nz8s0yf8AoK+/qaueO/EwsbU6fYyg3MwIkZDzGv8AQn/PaovAvhVLaGPVb+PNw/zQxsP9WOzH3P6f\nXoAWfAvhmXSI2vrzK3U6bRH/AHFyDz7nA+lddRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUU\nUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQ\nAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAB\nRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFc\n/wCMteGiaSfKbF3PlIh3X1b8P54roK8p1SRvFvjZbeFibcP5SMO0a8s348n8RQBr/DnQ2G/WbleW\nykAP/jzf0/Ou/qOCGO3gSGFAkcahVUdAB0FSUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAB\nRRRQAUUUUAFFFFAHH/EnUfs2ix2Sn57p+f8AdXk/rtq34B0/7D4bikZcSXTGU/Tov6DP41ynjqV9\nU8XQ6fCc+WEgA7bmOSf1H5V6ZDEkEEcMYwkahVHoAMCgCSiiigAooooAKKK5jxX4tg0WN7a2Ky35\nHCjkRe7e/tQBmePvExgR9IsnHmOuLhx/Cp/h+pHX2qj8MbG3nvL26ljDy24QRE/w7t2T9eKo6J4e\nlvdJ1PW9RDMq28rw7+sj7T859gf1+la3wr/5in/bL/2egD0CiiigAooooAKKKKACiimSSJEheV1R\nF6sxwBQA+iufv/Geh2RIN357j+GAb/16frXP3vxK6iw0/wCjzv8A+yj/ABoA9AryzxDLHB8ShNKw\nSOO5t2Zj0ACoSaH8Y+JdUHk2cYRn/wCfaEkkfjmsSSwvJfEEVjqbyR3M0saSPI29huxgk554I70A\nd5qvxD0+3Vk06N7uXsxGxB+fJ/L8a5s33izxQSIBN5Ddoh5cf03d/wASa7HTfA2i2JDSRNdyDndO\ncj/vkcfnmujVVRQqKFUDAAGAKAOE0P4eGG4iuNVuEk2Nu8iMZB+pP8sV3lLRQAUUUUAFFFFABRRR\nQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFA\nBRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAF\nFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUU\nUUAFFFFABRRRQAUUUUAFFFFAGP4svX0/w1ezxNtk2BFPcFiFyPzzXNfDDTk8m71JuXLeQnsAAT+e\nR+VafxHkKeGSo6POin9T/Sn/AA8jCeFomHV5XY/nj+lAHT0UUUAFFFFABRRRQAUUUUAFFFFABRRR\nQAUUUUAFFFFABRRRQAUUUUAFFFQ3lylnZzXMn3IUZ2+gGaAPM9EXz/iU/wBp5YXMxwfUBsflj9K9\nSryrwS8Z1q81m/kCx20bys5P8bZ/UjdSza/4l8TXEkGniVIs/wCrt/lCg9Nz/wD18UAep0tYfhHT\nr/S9GFrqLozhyyBWLbQecE+uc/nW5QAUUVwPjPxeQX0rSXJc/LNMn/oK+/qaAJvFvjYWpksNJcNO\nPlknHIT2X1Pv2/lT8K+CpLlxqOtq21juWB/vOfV/8Pz97/g7walkiX+qRhro/NHE3SL3P+1/L612\ntAGdryqnhzUlUBVFpKAAMADYa5D4V/8AMU/7Zf8As9dh4g/5F7U/+vSX/wBANcX8LZolm1GFnUSS\nCNkUnlgN2cfTIoA9EooooAKKKKACiiigAqjrWnDVdIubEkKZUwrHoGHIP5gVeooA4uw+HOnw4a9u\nprlv7qjYv9T+tdDZeH9IsMG20+BWHRiu5h+Jya06KACvLvG/+i+NopyccRSZ+hx/SvUa5zxb4Wj1\n+JZonEV7Eu1GP3WHXafz6+9AHQqyuoZSGUjIIOQRTq8v8OeIr3w1f/2Xq6uLYNtZX5MJ9R6r7fiP\nf05HWRFeNgyMAVYHII9aAHUUUUAFFFFABWZreu2OhW6y3rtl8hI0GWfHXH/160mIVSzEAAZJPavK\ntcuD4r8YxWtq+6DcIY2HTaOWb+Z+gFAHomg6xDrmmreQqUyxVkJyVI7H8MH8a0qrWGn2mm24t7KB\nIYx2UdT6k9zVmgAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACii\nigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKK\nACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA\nKKKKACiiigAooooAKKKKACiiigAooooAKKKKAOS+JP8AyLaf9fC/yarPw/8A+RUtv99//QjVb4k/\n8i2n/Xwv8mqb4eSxv4XiRXUtG7hgDyuWJ5/CgDp6KKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKr3t7bWFu095OkMQ/ic4/L1NcJrfxCkkY2+iRFc8efIuWP8Aur/j+VAHd3t9a2EJ\nmvLiOCP1dsZ+nrXn/i3xtBqNlLp2nRuYpMB534yAc4A98d/yqtZeD9d12YXWqTNArfxzks5Hsvb6\nHFWPGHhnT9B8PQvaq7ztcqrTSHJI2scegHFAGRoPhHUtbhWePZBaseJZD97BwcAdf0r0rw7oMGgW\nBt4XMru26SQjBY/TsKqeAyD4RsgCCQZAfb941dASACScAdSaAFpkkiQxtJK6oiDLMxwAPUmuK8Qe\nP47WZrbSEjuGXhp25QH/AGQOv16fWsFNO8U+KmD3BlEBOQ0x8uMfRe/4CgDQ8TeNZb5zp+h79jnY\nZlB3yZ7KOo/n9K0/Bvg8adt1DUkBu+scR5EXuf8Aa/lWn4a8KWmhIJTie8I+aYj7vso7fXr/ACro\nKACiiigBrKrqVdQysMEEZBFeZ6j4I1mDVp5dLjUQ+YWhaOUIUU9Byc8DivTqKAOR8GaVrmn3dy+r\nvI0boAm+fzOc/U111FFABRRRQAUUUUAFFFFABRRRQAUUUUAc74u8Mx67aeZCFS+iH7t+m8f3T/T0\nrlPCfiuTRJDperK4t1bALA7oT3BHp/KvTawPE3hW016IyDEF4o+WYDr7N6j+VAG5FJHNEssTq8bj\nKspyCPUGn15OH8QeCbtA5P2dm4XO6KT1x6H8jXX23j7RJYEeaSWGQj5ozGW2n6jrQB1NJXOf8J3o\nH/P1J/35b/Cuf8WeN4byxNlpDSAScSzFduV/ujvz3oAi8X+J5tWujo+kbnhLbHaPkzN6D2/n9K6P\nwj4Vj0OH7RchZL+RfmPURj+6P6mqvgXwymnWqaldqGu5lygI/wBUp/qf/retdhQAUUUUAFFFFABR\nRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFF\nFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUU\nAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQA\nUUUUAFFFFABRRRQBU1PTrbVbKS0u03xP6HBB7EH1rzK5ttW8D6v51uxe3c4VyPklX0b0P+RXrFNk\njSVGSRFdGGCrDINAHKaP4+0292x3oNlMe7HKH8e3411UciTRrJE6ujDIZTkH8a5bV/AWmX26SzzZ\nTHn5BlD/AMB7fhiuVk0vxP4TlMtq0jQA5LQ/PGf95e34igD1aivMofiPqcZ23FnayY64DKf5mtGD\n4lwnH2jTJE9THKG/mBQB3lFcanxI0kj57W9B9lQ/+zVOnxB0Rjz9pX6xj+hoA6uiuaHjvQCATcyD\n2MLf4Uv/AAnegf8AP1J/35b/AAoA6Siub/4TvQP+fqT/AL8t/hR/wnegf8/Un/flv8KAOkormj47\n0AAn7TIfYQt/hUEnxD0RPurdP/uxj+pFAHWUVxUvxJ04A+TZXTntv2r/AFNZdz8Sb1+LSwgiz/z0\nYuf0xQB6TTWZUUs7BVHUk4Ary/8Atrxlqn/HulyEb/njb7R/31j+tKvhDxPqrg6hKVHXNzcb8fgM\n0AdtfeLdDsciS/jkcfww/OfzHH61y2rfEWabMOj2xj3cCWYZb8FHH55q5Y/De0TDX17LMf7sShB+\nZz/Sul0zw/pWlENZ2caSf89G+ZvzPIoA4C08LeIPEUwutTleFD/HcElsf7Kdv0FdzonhjTNFAa3h\n8ycdZ5OW/D0/CtmigArP1vSbfWtOezucgE7lcdUYdDWhRQB5RPofibw1I5smnaJ+N9qSwb6r1B/D\n8alXwp4p1XDXsjKDz/pVwT+gzivUqKAOW8M+C7bR2Fzdlbq8/hOPkj+gPf3rqaKKACiiigAooooA\nKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigCC8tLe+tntrqJZYXGGVhXNH4eaISSGuhns\nJBx+ldZRQByX/CvNF/v3f/fwf4Ve0zwdo2mS+bHbmaQfdac79v0HSt+igAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigCKa2guBieGOUejqG/nWdP4Z0S4z5mmWwz/cTZ/LFa1FAGA/grw8\n5ydPAPtK4/rUD+AtBbGIJV+kp/rXTUUAcmfh5opJO66HsJB/hSf8K80X+/d/9/B/hXW0UAcl/wAK\n80X+/d/9/B/hR/wrzRf793/38H+FdbRQByY+Hmigg7ro+xkH+FTx+BNAT71tI/8AvTN/QiulooAw\n4vB+gREFdNjOP7zs38zWnbafZWePstpBDj/nnGF/lVmigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiii\ngAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKA\nCiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAK\nKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAoo\nooAKKKKACiiigD//2Q==\n', 4),
(46, '32320fa', 'e34b8ba', 'Via Sant''Antonio da Padova, 11', 'uno', 'dos@tres.co', 'Corso Francia, 135', 'cinco', 'seir@tie.cofasf', 'waiting', '2014-09-22 09:45:38', NULL, NULL, NULL, 4),
(48, 'edba0ad', 'cfb42a6', 'Via Giovanni Carlo Cavalli, 26', 'FEDE', 'rico@g.co', 'Corso Vittorio Emanuele II, 150', 'carla', 'carala@fafa.co', 'waiting', '2014-09-22 13:17:21', NULL, NULL, NULL, 4),
(50, '5e932d7', '407f32b', 'Via Eusebio Garizio, 15', 'fede', 'fara@fasf.cd', 'Corso Francia, 123', 'fico', 'farroyave51@gmail.com', 'waiting', '2014-09-22 15:53:27', NULL, NULL, NULL, 4),
(51, 'a62c819', '5384f82', 'via magenta 60', 'uno', 'dos@tres.co', 'via magenta 50', 'cuatro', 'seis@siete.co', 'delivering', '2014-09-22 16:04:29', 1411670817, NULL, NULL, 4),
(53, '3a67bb0', '4627c47', 'via magenta 1', 'fede', 'rico@gmail.co', 'via gorizia 1', 'otro', 'fef@fd.cd', 'waiting', '2014-09-22 18:40:02', NULL, NULL, NULL, 1),
(54, 'fbdd6f5', 'afa8fcd', 'via roma 11', 'oroi', 'fasdkjf@dfa.fa', 'corso vittorio emmanuelle II 150', 'fasfg', 'fagf@faf.sfr', 'waiting', '2014-09-22 18:40:54', NULL, NULL, NULL, 1),
(55, 'ad5c68e', '437ec17', 'via caballi 20', 'fede', 'rica@g.co', 'via magenta 82', 'kiko', 'jiko@fa.vo', 'waiting', '2014-09-23 12:24:42', NULL, NULL, NULL, 1),
(56, '2273b13', 'f5d300c', 'via magenta 40', 'andrea lungo', 'alung@ponyexpress.com', 'corso ducca degli abbruzzi 24', 'federico colozzo', 'fecolo@ponyexpress.com', 'waiting', '2014-09-25 14:31:37', NULL, NULL, NULL, 1),
(57, '04f3602', '7584c14', 'via gorizia 45', 'carlo', 'carlo@pony.co', 'via roma 20', 'laura', 'lalepe@pny.co', 'waiting', '2014-09-25 14:34:29', NULL, NULL, NULL, 1),
(58, '2a244b3', 'ed23712', 'corso sebastopoli 250', 'francesca', 'francy@pon.co', 'via po 40', 'alice', 'alice25@pony.co', 'waiting', '2014-09-25 14:37:51', NULL, NULL, NULL, 1),
(59, '9634481', 'bb61c60', 'via magenta 51', 'federico', 'farroyave51@gmail.com', 'corso monginevro 200', 'anna', 'annina@pony.co', 'waiting', '2014-09-25 14:42:42', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `path_agent`
--

CREATE TABLE IF NOT EXISTS `path_agent` (
  `id_agent` int(11) NOT NULL,
  `p_order` int(2) NOT NULL,
  `id_delivery` int(10) NOT NULL,
  `pick_up` int(2) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `arrival_time_est` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `path_agent`
--

INSERT INTO `path_agent` (`id_agent`, `p_order`, `id_delivery`, `pick_up`, `latitude`, `longitude`, `arrival_time_est`) VALUES
(1, 10, 53, 0, 45.0501, 7.6414, 8520),
(4, 1, 46, 1, 45.0689, 7.66502, 1000),
(4, 3, 48, 1, 45.0717, 7.65947, 2386),
(4, 7, 46, 0, 45.0756, 7.64673, 3491),
(1, 6, 53, 1, 45.0625, 7.67667, 3183),
(4, 4, 51, 0, 45.0671, 7.66414, 2688),
(4, 5, 48, 0, 45.0719, 7.65388, 3096),
(4, 6, 50, 0, 45.0757, 7.64734, 3404),
(4, 2, 50, 1, 45.0737, 7.64383, 1794),
(1, 7, 54, 1, 45.0704, 7.68454, 4048),
(1, 8, 54, 0, 45.0719, 7.65388, 6358),
(1, 4, 55, 1, 45.0714, 7.66054, 1689),
(1, 5, 55, 0, 45.0675, 7.66291, 2155),
(1, 2, 56, 1, 45.0664, 7.666, 233),
(1, 3, 56, 0, 45.0623, 7.66285, 619),
(1, 9, 57, 1, 45.0501, 7.6414, 8520),
(1, 13, 57, 0, 45.0702, 7.68436, 14722),
(1, 11, 58, 1, 45.0479, 7.63762, 8993),
(1, 14, 58, 0, 45.0667, 7.6922, 15307),
(1, 1, 59, 1, 45.067, 7.66428, 10),
(1, 12, 59, 0, 45.0643, 7.6257, 10734);

-- --------------------------------------------------------

--
-- Struttura della tabella `question`
--

CREATE TABLE IF NOT EXISTS `question` (
`id` int(11) NOT NULL,
  `text` varchar(512) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `question`
--

INSERT INTO `question` (`id`, `text`) VALUES
(1, 'Is the packet in a good condition?'),
(2, 'Delivery time is related to the estimated time?'),
(3, 'What is the total score for the quality of the service?');

-- --------------------------------------------------------

--
-- Struttura della tabella `question_response`
--

CREATE TABLE IF NOT EXISTS `question_response` (
  `questionnaire_id` int(11) NOT NULL,
  `vote` float DEFAULT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `question_response`
--

INSERT INTO `question_response` (`questionnaire_id`, `vote`, `question_id`) VALUES
(29, 3, 3),
(30, 5, 1),
(30, 3, 2),
(30, 5, 3),
(33, 5, 1),
(33, 4, 2),
(33, 3, 3),
(34, 3, 1),
(34, 4, 2),
(34, 4, 3),
(35, 3, 1),
(35, 4, 2),
(35, 3, 3),
(36, 3, 1),
(36, 4, 2),
(36, 4, 3),
(37, 4, 1),
(37, 3, 2),
(37, 2, 3),
(38, 4, 1),
(38, 3, 2),
(38, 2, 3),
(39, 4, 1),
(39, 3, 2),
(39, 3, 3),
(40, 4, 1),
(40, 4, 2),
(40, 3, 3),
(41, 5, 1),
(41, 4, 2),
(41, 3, 3),
(42, 5, 1),
(42, 4, 2),
(42, 2, 3),
(43, 5, 1),
(43, 4, 2),
(43, 4, 3),
(44, 4, 1),
(44, 4, 2),
(44, 3, 3),
(45, 4, 1),
(45, 3, 2),
(45, 3, 3),
(47, 3, 1),
(47, 2, 2),
(47, 1, 3),
(49, 4, 1),
(49, 4, 2),
(49, 4, 3),
(52, 5, 1),
(52, 4, 2),
(52, 4, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_delivery_agent_idx` (`agent_id`);

--
-- Indexes for table `path_agent`
--
ALTER TABLE `path_agent`
 ADD PRIMARY KEY (`id_delivery`,`pick_up`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_response`
--
ALTER TABLE `question_response`
 ADD PRIMARY KEY (`questionnaire_id`,`question_id`), ADD KEY `fk_question_response_question1_idx` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `delivery`
--
ALTER TABLE `delivery`
ADD CONSTRAINT `fk_delivery_agent` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `question_response`
--
ALTER TABLE `question_response`
ADD CONSTRAINT `fk_question_response_question1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
