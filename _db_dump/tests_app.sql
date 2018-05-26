-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 26 2018 г., 10:52
-- Версия сервера: 5.7.22-0ubuntu0.16.04.1
-- Версия PHP: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tests_app`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `id` int(5) NOT NULL,
  `question_id` int(5) NOT NULL,
  `text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `text`, `is_correct`) VALUES
(71, 39, '120', 0),
(72, 39, '200', 0),
(73, 39, '240', 1),
(74, 39, '360', 0),
(75, 40, 'HTTP', 0),
(76, 40, 'FTP', 1),
(77, 41, '01', 0),
(78, 41, '1', 1),
(79, 41, '11', 0),
(84, 43, 'В Германии', 0),
(85, 43, 'В Израиле', 0),
(86, 43, 'В США', 0),
(87, 43, 'В Японии', 1),
(88, 44, '3 поколения', 0),
(89, 44, '4 поколения', 1),
(90, 44, '5 поколения', 0),
(91, 44, '2 поколения', 0),
(92, 45, '19 100', 1),
(93, 45, '20 000', 0),
(94, 45, '20 100', 0),
(95, 45, '19 000', 0),
(96, 46, 'Эмулятор', 0),
(97, 46, 'Транслятор', 1),
(98, 46, 'Программатор', 0),
(99, 46, 'Анализатор', 0),
(100, 47, '1С', 1),
(101, 47, 'Apple', 0),
(102, 47, 'Corel', 0),
(103, 47, 'Oracle', 0),
(125, 53, 'Чипсет', 0),
(126, 53, 'Порт', 0),
(127, 53, 'Сокет', 1),
(128, 53, 'Шина', 0),
(129, 54, '1', 1),
(130, 54, '2', 0),
(131, 54, '3', 0),
(132, 54, '4', 0),
(133, 54, '5', 0),
(134, 55, '1', 0),
(135, 55, '2', 1),
(136, 55, '3', 0),
(137, 55, '4', 0),
(138, 55, '5', 0),
(139, 56, 'Ы', 0),
(140, 56, 'В', 0),
(141, 56, 'Ы', 1),
(142, 56, 'В', 0),
(143, 56, 'В', 0),
(144, 57, 'ЦЫВ', 0),
(145, 57, 'ЦЫВ', 0),
(146, 57, 'ЫВ', 0),
(147, 57, 'ЫФ', 1),
(148, 57, 'ЙФ', 0),
(149, 58, 'ЙЦФЫ', 1),
(150, 58, 'ФЫ', 0),
(151, 58, 'ЙЦФЫ', 0),
(152, 58, 'Ф', 0),
(153, 58, 'ЙЦФ', 0),
(154, 59, 'ДЛО', 0),
(155, 59, 'ЛОР', 1),
(156, 59, 'ОР', 0),
(157, 59, 'РО', 0),
(158, 59, 'РОП', 0),
(159, 60, 'УВЫ', 0),
(160, 60, 'УЦВЫ', 0),
(161, 60, 'ВЫ', 0),
(162, 60, 'УКВЫ', 1),
(163, 60, 'УКВЫ', 0),
(164, 61, 'КВ', 0),
(165, 61, 'АВ', 0),
(166, 61, 'В', 0),
(167, 61, 'В', 0),
(168, 61, 'ЫФ', 1),
(169, 62, 'ЫФ', 0),
(170, 62, 'ФЫЯ', 0),
(171, 62, 'ЫЯ', 0),
(172, 62, 'Ы', 0),
(173, 62, 'ФЫ', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(5) NOT NULL,
  `test_id` int(5) NOT NULL,
  `text` text NOT NULL,
  `hint_text` text NOT NULL,
  `theory_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `test_id`, `text`, `hint_text`, `theory_text`) VALUES
(39, 20, ' Автоматическое устройство осуществило перекодировку информационного сообщения на русском языке длиной в 30 символов, первоначально записанного в 16-битном коде Unicode, в 8-битную кодировку КОИ-8. На сколько бит уменьшилась длина сообщения? В ответе выберите только число.', 'Тут подсказка...', 'Тут теория...'),
(40, 20, ' Для доступа к файлам, хранящимся на серверах файловых архивов, используется протокол ...', ' Подсказка', ' Теория'),
(41, 20, ' Определить значение логического выражения, если A – истина, В – ложь, С – ложь:\r\n\r\nне (А U В U С) U A', 'Подск', 'Тео'),
(43, 20, ' История флешки удивительна, поскольку полюбившейся нам предмет постоянно находится в состоянии эволюции: увеличивается объём памяти, и уменьшаются размеры устройства. Современные USB накопители удобные, компактные и вместительные, на них очень просто что-то записать. В какой стране была изобретена полупроводниковая перепрограммируемая флеш-память?', ' Под', 'История создания флешек началась в 1984 году в Японии, с изобретением компанией Toshiba полупроводниковой перепрограммируемой флеш-памяти. В 1989 году появился первый чип с флеш-памятью типа NAND, большая плотность компоновки которой позволила создавать микросхемы значительного объема.'),
(44, 21, ' Развитие вычислительной техники в современном периоде принято рассматривать с точки зрения смены поколений компьютеров. Каждое поколение компьютеров в начальный момент развития характеризуется качественным скачком в росте основных характеристик компьютера, вызванным обычно переходом на новую элементную базу, а также относительной стабильностью архитектурных и логических решений. Сколько на данный момент времени существует поколений компьютерной техники?', ' Под', 'Разбиение поколений компьютеров по годам весьма условно. В то время, как начиналось активное использование компьютеров одного поколения, создавались посылки для возникновения следующего. На данный момент существует четыре поколения компьютерной техники, разработка компьютеров пятого поколения находится лишь в планах.'),
(45, 21, ' \r\nВ результате компьютерного сбоя в первые часы 2000 года посетители одной из Интернет – странички оказались в далеком будущем. Какой год они увидели на экране?', ' Подсказка тут', ' Как это ни странно, но во многих первых программах для вывода даты использовали формат 19хх, где 19 было фиксированным набором символов, и изменялись только две последние цифры. Поэтому вслед за 1999 пришел 19100 год.'),
(46, 21, ' Академик Андрей Петрович Ершов - один из основоположников школьной информатики. В 1958 г. вышла его книга «Программирующая программа для быстродействующей электронной счетной машины». Как называют системную программу такого типа в настоящее время?', 'Подск', 'Поскольку текст, записанный на языке программирования, непонятен компьютеру, то требуется перевести его на машинный код. Такой перевод программы с языка программирования на язык машинных кодов называется трансляцией, а выполняется она специальными программами – трансляторами.\r\n\r\nТранслятор - обслуживающая программа, преобразующая исходную программу, предоставленную на входном языке программирования, в рабочую программу, представленную на объектном языке.\r\n\r\nВ настоящее время трансляторы разделяются на три основные группы: ассемблеры, компиляторы и интерпретаторы.'),
(47, 21, 'Название какой компании было вначале названием её собственной поисковой программы, которой требовалось не более одной секунды для получения требуемой информации.', ' Под', 'Название компании 1С было вначале названием её собственной поисковой программы: не более 1С (одной секунды) требовалось для получения требуемой информации.'),
(53, 25, ' Как называется разъем для установки центрального процессора?', ' расположен на материнской плате, внешне выглядит как квадратная пластина с множеством отверстий, количество которых соответствует ножкам процессора ', ' Английский термин сокет (socket) переводится на русский язык как "разъём" или "гнездо". В компьютерной тематике этот термин относится к процессору и материнской плате, для совместной работы которых очень важно, чтобы сокет был одинаковый, иначе ничего работать не будет. '),
(54, 25, ' 1', ' 1', ' 1'),
(55, 25, ' ККУ', ' УКУ', ' УКУ'),
(56, 25, ' Н', ' А', ' ВЫ'),
(57, 25, ' УЦВЫ', ' ФВЫ', ' ЦФЫ'),
(58, 25, ' ЦУВЫ', ' ЦУЫ', ' ЙЦЫФ'),
(59, 25, ' ЙЦФЫ', ' ЫФ', ' ЫФ'),
(60, 25, ' ВЫ', ' ВЫ', ' ВЫ'),
(61, 25, ' АПРО', ' ОР', ' РПА'),
(62, 25, ' Ы', ' Ы', ' Ы');

-- --------------------------------------------------------

--
-- Структура таблицы `results`
--

CREATE TABLE `results` (
  `id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `test_id` int(5) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `results`
--

INSERT INTO `results` (`id`, `user_id`, `test_id`, `date`) VALUES
(92, 59, 21, '2018-05-06 22:49:32'),
(93, 59, 21, '2018-05-06 22:50:40'),
(94, 59, 20, '2018-05-06 22:51:30'),
(102, 59, 21, '2018-05-08 06:09:44'),
(103, 70, 20, '2018-05-08 11:42:29'),
(104, 70, 21, '2018-05-08 11:43:11'),
(105, 70, 25, '2018-05-08 11:44:01'),
(106, 70, 21, '2018-05-08 11:56:50'),
(107, 80, 20, '2018-05-08 12:09:53'),
(108, 80, 21, '2018-05-08 12:10:09'),
(109, 80, 25, '2018-05-08 12:10:47'),
(110, 81, 20, '2018-05-08 12:11:48'),
(111, 81, 21, '2018-05-08 12:12:06'),
(112, 81, 25, '2018-05-08 12:12:42'),
(113, 82, 21, '2018-05-11 08:18:10');

-- --------------------------------------------------------

--
-- Структура таблицы `results_answers`
--

CREATE TABLE `results_answers` (
  `id` int(5) NOT NULL,
  `result_id` int(5) NOT NULL,
  `answer_id` int(5) NOT NULL,
  `use_hint` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `results_answers`
--

INSERT INTO `results_answers` (`id`, `result_id`, `answer_id`, `use_hint`) VALUES
(205, 92, 91, 0),
(206, 92, 92, 0),
(207, 92, 97, 0),
(208, 92, 100, 0),
(209, 93, 90, 1),
(210, 93, 94, 0),
(211, 93, 97, 1),
(212, 93, 100, 0),
(213, 94, 72, 0),
(214, 94, 76, 1),
(215, 94, 78, 0),
(217, 94, 87, 0),
(226, 102, 91, 1),
(227, 102, 94, 0),
(228, 102, 97, 0),
(229, 102, 100, 0),
(230, 103, 73, 0),
(231, 103, 76, 0),
(232, 103, 78, 0),
(234, 103, 86, 0),
(235, 104, 88, 0),
(236, 104, 94, 0),
(237, 104, 98, 1),
(238, 104, 100, 0),
(239, 105, 127, 0),
(240, 105, 129, 0),
(241, 105, 136, 0),
(242, 105, 142, 0),
(243, 105, 145, 0),
(244, 105, 151, 0),
(245, 105, 158, 0),
(246, 105, 161, 0),
(247, 105, 164, 1),
(248, 105, 173, 0),
(249, 106, 88, 0),
(250, 106, 94, 1),
(251, 106, 97, 1),
(252, 106, 100, 1),
(253, 107, 71, 0),
(254, 107, 76, 0),
(255, 107, 78, 0),
(257, 107, 87, 0),
(258, 108, 91, 0),
(259, 108, 94, 0),
(260, 108, 97, 0),
(261, 108, 100, 0),
(262, 109, 127, 0),
(263, 109, 129, 1),
(264, 109, 135, 0),
(265, 109, 141, 0),
(266, 109, 147, 0),
(267, 109, 153, 0),
(268, 109, 155, 0),
(269, 109, 160, 0),
(270, 109, 168, 0),
(271, 109, 173, 0),
(272, 110, 71, 0),
(273, 110, 76, 0),
(274, 110, 78, 0),
(276, 110, 87, 0),
(277, 111, 91, 0),
(278, 111, 92, 1),
(279, 111, 98, 0),
(280, 111, 100, 0),
(281, 112, 127, 1),
(282, 112, 129, 0),
(283, 112, 135, 0),
(284, 112, 142, 0),
(285, 112, 147, 0),
(286, 112, 150, 0),
(287, 112, 155, 0),
(288, 112, 161, 0),
(289, 112, 168, 0),
(290, 112, 173, 0),
(291, 113, 91, 0),
(292, 113, 92, 1),
(293, 113, 97, 0),
(294, 113, 100, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`id`, `name`, `create_date`) VALUES
(20, 'Информация и ее кодирование', '2018-05-02 20:12:08'),
(21, 'Тест по информатике', '2018-05-02 20:22:22'),
(25, 'Архитектура компьютера', '2018-05-08 11:28:06');

-- --------------------------------------------------------

--
-- Структура таблицы `usergroups`
--

CREATE TABLE `usergroups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`) VALUES
(1, 'SuperAdministrator'),
(2, 'Administrator'),
(3, 'Registered'),
(4, 'Notactivated');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `soname` varchar(255) DEFAULT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT 'default.png',
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activation_code` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `login`, `password`, `name`, `soname`, `avatar`, `registration_date`, `activation_code`, `status`) VALUES
(59, 'admin@domain.com', 'admin', '3bb310a193dfe4607c2a79e25c5dee93', 'Mr.', 'Teacher', 'default.png', '2018-04-09 21:34:33', NULL, 1),
(70, 'ivanov@inbox.ru', 'ivanov', '81dc9bdb52d04dc20036dbd8313ed055', 'Иван', 'Иванов', 'default.png', '2018-04-16 10:58:16', NULL, 1),
(80, 'qwe@mail.ru', 'kata', '1679091c5a880faf6fb5e6087eb1b2dc', 'катя', 'петрова', 'default.png', '2018-05-08 12:08:29', NULL, 1),
(81, 'idj@mail.ru', 'masha', 'c4ca4238a0b923820dcc509a6f75849b', 'Маша', 'Булкина', 'default.png', '2018-05-08 12:11:25', NULL, 1),
(82, 'angelinakam1996@mail.ru', '1', 'c4ca4238a0b923820dcc509a6f75849b', 'Ангелина', 'Каменева', 'default.png', '2018-05-11 08:17:03', NULL, 1),
(83, 'liza@inbox.ru', 'liza', 'c4ca4238a0b923820dcc509a6f75849b', 'Лиза', 'Золотых', 'default.png', '2018-05-17 08:20:41', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_has_group`
--

CREATE TABLE `user_has_group` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_has_group`
--

INSERT INTO `user_has_group` (`id`, `user_id`, `group_id`) VALUES
(5, 59, 1),
(4, 59, 3),
(16, 70, 3),
(26, 80, 3),
(27, 81, 3),
(28, 82, 3),
(29, 83, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(5) NOT NULL,
  `session_id` varchar(1024) NOT NULL,
  `user_id` int(5) NOT NULL,
  `last_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Индексы таблицы `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Индексы таблицы `results_answers`
--
ALTER TABLE `results_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`,`answer_id`),
  ADD KEY `results_answers_ibfk_1` (`answer_id`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `usergroups`
--
ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_has_group`
--
ALTER TABLE `user_has_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`group_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;
--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT для таблицы `results`
--
ALTER TABLE `results`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
--
-- AUTO_INCREMENT для таблицы `results_answers`
--
ALTER TABLE `results_answers`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;
--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `usergroups`
--
ALTER TABLE `usergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT для таблицы `user_has_group`
--
ALTER TABLE `user_has_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT для таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `results_answers`
--
ALTER TABLE `results_answers`
  ADD CONSTRAINT `results_answers_ibfk_1` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `results_answers_ibfk_2` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_has_group`
--
ALTER TABLE `user_has_group`
  ADD CONSTRAINT `user_has_group_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_has_group_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `usergroups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
