-- Hello Travel — MySQL 5.7+/8.0 / MariaDB 10.3+ schema with sample data
-- Usage: mysql -u root -p < database/schema.sql

CREATE DATABASE IF NOT EXISTS hello_travel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hello_travel;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS enquiries, contact_messages, subscribers, testimonials, package_days, packages, destinations;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE destinations (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug         VARCHAR(120) NOT NULL UNIQUE,
    name         VARCHAR(120) NOT NULL,
    country      VARCHAR(120) NOT NULL,
    region       VARCHAR(60)  NOT NULL,
    summary      VARCHAR(300) NOT NULL,
    description  TEXT         NOT NULL,
    best_time    VARCHAR(120) NOT NULL DEFAULT 'Year-round',
    image_url    VARCHAR(500) NULL,
    accent_color VARCHAR(7)   NOT NULL DEFAULT '#1d3cff',
    is_featured  TINYINT(1)   NOT NULL DEFAULT 0,
    is_active    TINYINT(1)   NOT NULL DEFAULT 1,
    sort_order   INT          NOT NULL DEFAULT 0,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_dest_region (region)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE packages (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    destination_id INT UNSIGNED NOT NULL,
    slug           VARCHAR(160) NOT NULL UNIQUE,
    title          VARCHAR(160) NOT NULL,
    summary        VARCHAR(300) NOT NULL,
    description    TEXT         NOT NULL,
    highlights     TEXT         NULL COMMENT 'One per line',
    included       TEXT         NULL COMMENT 'One per line',
    style          VARCHAR(40)  NOT NULL,
    duration_days  SMALLINT UNSIGNED NOT NULL,
    group_size     SMALLINT UNSIGNED NOT NULL DEFAULT 12,
    price          DECIMAL(10,2) NOT NULL,
    currency       CHAR(3)      NOT NULL DEFAULT 'USD',
    rating         DECIMAL(2,1) NOT NULL DEFAULT 5.0,
    image_url      VARCHAR(500) NULL,
    accent_color   VARCHAR(7)   NOT NULL DEFAULT '#1d3cff',
    is_featured    TINYINT(1)   NOT NULL DEFAULT 0,
    is_active      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pkg_style (style),
    INDEX idx_pkg_price (price),
    CONSTRAINT fk_pkg_dest FOREIGN KEY (destination_id) REFERENCES destinations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE package_days (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id  INT UNSIGNED NOT NULL,
    day_number  SMALLINT UNSIGNED NOT NULL,
    title       VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    UNIQUE KEY uq_pkg_day (package_id, day_number),
    CONSTRAINT fk_day_pkg FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE testimonials (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_name  VARCHAR(120) NOT NULL,
    trip_name    VARCHAR(160) NOT NULL,
    quote        TEXT NOT NULL,
    rating       DECIMAL(2,1) NOT NULL DEFAULT 5.0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE enquiries (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id  INT UNSIGNED NULL,
    name        VARCHAR(120) NOT NULL,
    email       VARCHAR(190) NOT NULL,
    phone       VARCHAR(40)  NULL,
    travel_date DATE NULL,
    travellers  SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    message     TEXT NULL,
    status      ENUM('new','contacted','booked','closed') NOT NULL DEFAULT 'new',
    ip_address  VARCHAR(45) NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_enq_status (status),
    CONSTRAINT fk_enq_pkg FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120) NOT NULL,
    email       VARCHAR(190) NOT NULL,
    phone       VARCHAR(40)  NULL,
    destination VARCHAR(120) NULL,
    budget      VARCHAR(40)  NULL,
    message     TEXT NOT NULL,
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    ip_address  VARCHAR(45) NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscribers (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(190) NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- sample data
INSERT INTO destinations (id, slug, name, country, region, summary, description, best_time, accent_color, is_featured, sort_order) VALUES
(1, 'kyoto', 'Kyoto', 'Japan', 'Asia', 'Temples, tea houses and quiet gardens in Japan''s ancient capital.', 'Kyoto holds more than a thousand temples, a living geisha culture and some of the finest food in Asia.\nWe travel early and late to find the city at its calmest, with local guides who open doors most visitors never see.', 'Mar–May, Oct–Nov', '#ff4d2e', 1, 1),
(2, 'lisbon', 'Lisbon', 'Portugal', 'Europe', 'Sun-washed hills, tiled façades and Atlantic light.', 'Lisbon is a city of viewpoints and trams, fado bars and pastry shops.\nOur journeys pair the capital with the vineyards of the Alentejo and the wild coast of the Algarve.', 'Apr–Jun, Sep–Oct', '#1d3cff', 1, 2),
(3, 'marrakech', 'Marrakech', 'Morocco', 'Africa', 'Souks, riads and the edge of the Sahara.', 'From the colour and noise of the medina to silent nights under desert stars, Morocco rewards travellers who slow down.\nStay in family-run riads and cross the Atlas mountains with Berber guides.', 'Mar–May, Sep–Nov', '#ffb000', 1, 3),
(4, 'reykjavik', 'Reykjavík', 'Iceland', 'Europe', 'Glaciers, geysers and the northern lights.', 'Iceland is raw, elemental and endlessly photogenic.\nWe travel in small 4x4 groups to reach black-sand beaches, ice caves and hot springs away from the tour buses.', 'Jun–Aug, Sep–Mar for aurora', '#00a383', 1, 4),
(5, 'cusco', 'Cusco', 'Peru', 'Americas', 'Gateway to the Sacred Valley and Machu Picchu.', 'High in the Andes, Cusco blends Inca stonework with colonial churches.\nOur Peru journeys acclimatise gently before trekking to Machu Picchu.', 'May–Sep', '#8a2be2', 1, 5),
(6, 'queenstown', 'Queenstown', 'New Zealand', 'Oceania', 'Adventure capital beneath dramatic alpine peaks.', 'Lakes, fjords and mountains make Queenstown the ideal base for exploring New Zealand''s South Island.\nHike, kayak and taste your way through Central Otago wine country.', 'Dec–Mar', '#111111', 1, 6),
(7, 'hanoi', 'Hanoi', 'Vietnam', 'Asia', 'Street food, lantern-lit lanes and Ha Long Bay.', 'Hanoi''s Old Quarter hums from dawn to midnight.\nWe combine it with an overnight cruise on Lan Ha Bay and the rice terraces of the north.', 'Oct–Apr', '#e6007e', 0, 7),
(8, 'cape-town', 'Cape Town', 'South Africa', 'Africa', 'Table Mountain, winelands and safari add-ons.', 'Cape Town sits between mountain and ocean with a food and wine scene to match.\nExtend into the Kruger for a classic safari finale.', 'Oct–Apr', '#0077b6', 0, 8);

INSERT INTO packages (id, destination_id, slug, title, summary, description, highlights, included, style, duration_days, group_size, price, rating, accent_color, is_featured) VALUES
(1, 1, 'classic-japan', 'Classic Japan: Tokyo to Kyoto', 'Neon cities, mountain onsen and temple mornings in ten unhurried days.', 'Begin in Tokyo, ride the bullet train to the Japanese Alps and finish in Kyoto.\nStay in a traditional ryokan, learn to cook with a local chef and walk the Fushimi Inari gates at dawn.', 'Private dawn visit to Fushimi Inari\nOvernight in a Hakone ryokan with onsen\nHome-cooking class in Kyoto\nBullet train rail pass included', '9 nights boutique accommodation\nDaily breakfast, 4 dinners\nExpert local guide throughout\nAll rail travel and entrance fees', 'Culture', 10, 12, 4290, 4.9, '#ff4d2e', 1),
(2, 2, 'portugal-coast-to-vine', 'Portugal: Coast to Vine', 'Lisbon, Alentejo wine estates and the Algarve cliffs.', 'A relaxed week pairing city life with slow countryside days.\nTaste your way through family wineries and walk the Seven Hanging Valleys trail.', 'Tram-and-tile walking tour of Lisbon\nWine estate stay in the Alentejo\nSeven Hanging Valleys coastal hike', '7 nights hotels & quintas\nDaily breakfast, 3 dinners\nPrivate transfers\nWine tastings', 'Food & Wine', 8, 12, 2390, 4.8, '#1d3cff', 1),
(3, 3, 'morocco-desert-nights', 'Morocco: Medina to Sahara', 'From Marrakech riads to a luxury camp in the Erg Chebbi dunes.', 'Cross the High Atlas via ancient kasbahs, then trade the car for camels as the Sahara comes into view.\nEnd back in Marrakech for a cooking class and hammam.', 'Night in a luxury desert camp\nAit Benhaddou kasbah\nMarrakech souk tour with a local', '8 nights riads & desert camp\nDaily breakfast, 5 dinners\nPrivate 4x4 with driver-guide', 'Adventure', 9, 10, 1990, 4.9, '#ffb000', 1),
(4, 4, 'iceland-ring-road', 'Iceland Ring Road Explorer', 'Circle the island by 4x4: waterfalls, glaciers and whale watching.', 'Follow Route 1 around the entire island with nights in cosy countryside lodges.\nWalk behind waterfalls, cruise a glacier lagoon and bathe in geothermal pools.', 'Glacier hike on Vatnajökull\nJökulsárlón iceberg lagoon\nWhale watching in Húsavík', '9 nights lodges\nDaily breakfast\n4x4 transport & guide\nGlacier equipment', 'Adventure', 10, 8, 4990, 4.7, '#00a383', 0),
(5, 5, 'inca-trail-machu-picchu', 'Inca Trail to Machu Picchu', 'Trek the classic Inca Trail and arrive at the Sun Gate at sunrise.', 'Acclimatise in Cusco and the Sacred Valley before a four-day trek on the original Inca Trail.\nPorters carry the gear; you carry only the views.', 'Classic 4-day Inca Trail\nSunrise at the Sun Gate\nSacred Valley market day', '9 nights hotels & camping\nMost meals on trek\nTrail permits & porters', 'Adventure', 10, 12, 3150, 4.9, '#8a2be2', 0),
(6, 6, 'south-island-escape', 'South Island Escape', 'Fjords, lakes and wine country on New Zealand''s wild South Island.', 'Cruise Milford Sound, hike the Routeburn Track and toast the trip in Central Otago.\nA perfect blend of active days and relaxed evenings.', 'Milford Sound cruise\nRouteburn day hike\nCentral Otago wine tasting', '11 nights lodges\nDaily breakfast, 5 dinners\nAll activities listed', 'Adventure', 12, 12, 5690, 4.8, '#111111', 0),
(7, 7, 'vietnam-north-to-bay', 'Vietnam: Hanoi & the Bay', 'Street-food Hanoi, an overnight bay cruise and Sapa rice terraces.', 'Eat your way through the Old Quarter, sleep aboard a traditional junk and trek between hill-tribe villages.', 'Hanoi street-food night tour\nOvernight Lan Ha Bay cruise\nSapa homestay', '7 nights hotels, boat & homestay\nMost meals\nLocal guides', 'Culture', 8, 12, 1690, 4.7, '#e6007e', 0),
(8, 8, 'cape-and-kruger', 'Cape Town & Kruger Safari', 'Table Mountain, winelands and three days on safari.', 'Start by the ocean in Cape Town, explore the Cape Winelands, then fly to a private Kruger reserve for game drives.', 'Table Mountain cable car\nStellenbosch wine day\nBig Five game drives', '9 nights hotels & safari lodge\nAll meals on safari\nInternal flight', 'Wildlife', 10, 10, 5290, 4.9, '#0077b6', 1);

INSERT INTO package_days (package_id, day_number, title, description) VALUES
(1, 1, 'Arrive in Tokyo', 'Meet your guide and group for a welcome dinner in an izakaya.'),
(1, 2, 'Tokyo old & new', 'Asakusa temples in the morning, Shibuya and Harajuku by afternoon.'),
(1, 3, 'Tokyo to Hakone', 'Travel to the mountains; evening onsen at a traditional ryokan.'),
(1, 4, 'Hakone to Kanazawa', 'Scenic rail to Kanazawa and the Kenroku-en garden.'),
(1, 5, 'Takayama', 'Morning market and preserved Edo-era streets.'),
(1, 6, 'Kyoto', 'Bullet train to Kyoto; evening walk in Gion.'),
(1, 7, 'Kyoto temples', 'Dawn at Fushimi Inari, afternoon at Kinkaku-ji.'),
(1, 8, 'Cooking class', 'Market visit and home-cooking class with a local chef.'),
(1, 9, 'Nara day trip', 'Tōdai-ji temple and the deer park; farewell dinner.'),
(1, 10, 'Depart', 'Transfer to Kansai airport.'),
(2, 1, 'Arrive in Lisbon', 'Welcome dinner in Alfama.'),
(2, 2, 'Lisbon', 'Tram, tiles and pastéis de nata walking tour.'),
(2, 3, 'Sintra', 'Palaces and gardens in the hills.'),
(2, 4, 'Alentejo', 'Drive to a wine estate; tasting and dinner.'),
(2, 5, 'Évora', 'Roman temple and cork forests.'),
(2, 6, 'Algarve', 'Coastal drive and sea caves by boat.'),
(2, 7, 'Seven Hanging Valleys', 'Clifftop hike and farewell seafood dinner.'),
(2, 8, 'Depart', 'Transfer to Faro airport.'),
(3, 1, 'Arrive in Marrakech', 'Riad check-in and rooftop dinner.'),
(3, 2, 'Medina', 'Souks, Bahia Palace and Jardin Majorelle.'),
(3, 3, 'High Atlas', 'Cross the Tizi n''Tichka pass to Aït Benhaddou.'),
(3, 4, 'Dades Gorge', 'Canyon walks and Berber village visits.'),
(3, 5, 'Into the Sahara', 'Camel trek to a luxury desert camp.'),
(3, 6, 'Desert sunrise', 'Dunes at dawn, then drive to the Draa Valley.'),
(3, 7, 'Ouarzazate', 'Film studios and kasbahs.'),
(3, 8, 'Marrakech', 'Cooking class and hammam.'),
(3, 9, 'Depart', 'Transfer to the airport.');

INSERT INTO testimonials (author_name, trip_name, quote, rating, created_at) VALUES
('Priya S.', 'Classic Japan', 'Every day felt effortless. Our guide knew exactly when to arrive to beat the crowds — Fushimi Inari at dawn was magical.', 5.0, '2026-06-02 10:00:00'),
('Daniel & Mia', 'Morocco: Medina to Sahara', 'The desert camp was the highlight of our year. Small group, great people, zero stress.', 5.0, '2026-05-18 10:00:00'),
('Tomás R.', 'Portugal: Coast to Vine', 'Perfect balance of city and countryside. The wine estate stay was unforgettable.', 4.8, '2026-04-11 10:00:00');
