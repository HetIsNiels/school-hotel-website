# (School) Hotel website
Made for school: Website to book hotel rooms.

This website was made in just one week, the code isn't that good.

## SQL export
````sql
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(25) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` int(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(25) NOT NULL,
  `room` int(25) NOT NULL,
  `customer` int(25) NOT NULL,
  `people` int(25) NOT NULL,
  `date_start` varchar(225) NOT NULL,
  `date_end` varchar(225) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(25) NOT NULL,
  `name` varchar(225) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `image` varchar(225) NOT NULL,
  `type` int(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

INSERT INTO `rooms` (`id`, `name`, `description`, `price`, `image`, `type`) VALUES
(1, 'Room #101', 'Room #101', '58.00', 'assets/img/rooms/room101.jpg', 1),
(2, 'Room #102', 'Room #102', '29.00', 'assets/img/rooms/room102.jpg', 1),
(3, 'Room #103', 'Room #103', '24.00', 'assets/img/rooms/room103.jpg', 1),
(4, 'Room #104', 'Room #104', '29.00', 'assets/img/rooms/room104.jpg', 3),
(5, 'Room #105', 'Room #105', '24.00', 'assets/img/rooms/room105.jpg', 3),
(6, 'Room #106', 'Room #106', '58.00', 'assets/img/rooms/room106.jpg', 4),
(7, 'Room #201', 'Room #201', '50.00', 'assets/img/rooms/room201.jpg', 3),
(8, 'Room #202', 'Room #202', '41.00', 'assets/img/rooms/room202.jpg', 4),
(9, 'Room #203', 'Room #203', '72.00', 'assets/img/rooms/room203.jpg', 2),
(10, 'Room #204', 'Room #204', '72.00', 'assets/img/rooms/room204.jpg', 3),
(11, 'Room #205', 'Room #205', '41.00', 'assets/img/rooms/room205.jpg', 2),
(12, 'Room #206', 'Room #206', '50.00', 'assets/img/rooms/room206.jpg', 1);

CREATE TABLE IF NOT EXISTS `room_categories` (
  `id` int(25) NOT NULL,
  `name` varchar(225) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `room_categories` (`id`, `name`) VALUES
(1, 'Family rooms'),
(2, 'Other rooms');

CREATE TABLE IF NOT EXISTS `room_types` (
  `id` int(25) NOT NULL,
  `name` varchar(225) NOT NULL,
  `category` int(25) NOT NULL,
  `people` int(25) NOT NULL DEFAULT '2'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `room_types` (`id`, `name`, `category`, `people`) VALUES
(1, '4 persons', 1, 4),
(2, 'Luxe room', 1, 4),
(3, 'Love room', 2, 2),
(4, 'Single room', 2, 1);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(25) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(60) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$12$1KkWGTMV1eaiwpC9hXahhuy26QIG8AYkV7EYWnSDBIe//y4BixpSC');

ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);
ALTER TABLE `room_categories`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
 ````