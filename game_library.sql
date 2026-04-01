-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: database
-- Время создания: Мар 24 2026 г., 11:47
-- Версия сервера: 8.0.45
-- Версия PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `game_library`
--

-- --------------------------------------------------------

--
-- Структура таблицы `achievements`
--

CREATE TABLE `achievements` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `icon_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `achievements`
--

INSERT INTO `achievements` (`id`, `game_id`, `title`, `description`, `icon_url`) VALUES
(1, 12, 'Charmed', 'Acquire your first Charm', 'img/achievements/HK/charmed.jpg'),
(2, 12, 'Falsehood', 'Defeat the False Knight', 'img/achievements/HK/falsehood.jpg'),
(3, 12, 'Test of Resolve', 'Defeat Hornet in Greenpath', 'img/achievements/HK/test_of_resolve.jpg'),
(4, 12, 'Connection', 'Open half of Hallownest\'s Stag Stations', 'img/achievements/HK/connection.jpg'),
(5, 12, 'Protected', 'Acquire 4 Mask Shards', 'img/achievements/HK/protected.jpg'),
(6, 12, 'Illumination', 'Defeat the Soul Master', 'img/achievements/HK/illumination.jpg'),
(7, 12, 'Respect', 'Defeat the Mantis Lords', 'img/achievements/HK/respect.jpg'),
(8, 12, 'Soulful', 'Acquire 3 Vessel Fragments', 'img/achievements/HK/soulful.jpg'),
(9, 12, 'Honour', 'Defeat the Dung Defender', 'img/achievements/HK/honour.jpg'),
(10, 12, 'Release', 'Defeat the Broken Vessel', 'img/achievements/HK/release.jpg'),
(11, 12, 'Enchanted', 'Acquire half of Hallownest\'s Charms', 'img/achievements/HK/enchanted.jpg'),
(12, 12, 'Beast', 'Destroy Herrah the Beast', 'img/achievements/HK/beast.jpg'),
(13, 12, 'Proof of Resolve', 'Defeat Hornet in Kingdom\'s Edge', 'img/achievements/HK/proof_resolve.jpg'),
(14, 12, 'Grubfriend', 'Rescue half of the imprisoned grubs', 'img/achievements/HK/grubfriend.jpg'),
(15, 12, 'Attunement', 'Collect 600 Essence', 'img/achievements/HK/attunement.jpg'),
(16, 12, 'Teacher', 'Destroy Monomon the Teacher', 'img/achievements/HK/teacher.jpg'),
(17, 12, 'Warrior', 'Complete the Trial of the Warrior', 'img/achievements/HK/warrior.jpg'),
(18, 12, 'Watcher', 'Destroy Lurien the Watcher', 'img/achievements/HK/watcher.jpg'),
(19, 12, 'Cartographer', 'Acquire a map of each area', 'img/achievements/HK/cartographer.jpg'),
(20, 12, 'Conqueror', 'Complete the Trial of the Conqueror', 'img/achievements/HK/conqueror.jpg'),
(21, 12, 'Execution', 'Defeat the Traitor Lord', 'img/achievements/HK/execution.jpg'),
(22, 12, 'Hope', 'Open all of Hallownest\'s Stag Stations', 'img/achievements/HK/hope.jpg'),
(23, 12, 'Obsession', 'Defeat the Collector', 'img/achievements/HK/obsession.jpg'),
(24, 12, 'Peace', 'Bring peace to the Mourner', 'img/achievements/HK/peace.jpg'),
(25, 12, 'Awakening', 'Collect 1800 Essence and awaken the Dream Nail', 'img/achievements/HK/awakening.jpg'),
(26, 12, 'Rivalry', 'Defeat Zote in the Colosseum of Fools', 'img/achievements/HK/rivalry.jpg'),
(27, 12, 'Grand Performance', 'Defeat Troupe Master Grimm', 'img/achievements/HK/grand_performance.jpg'),
(28, 12, 'Strength', ' fully upgrade the Nail', 'img/achievements/HK/strength.jpg'),
(29, 12, 'Metamorphosis', 'Rescue all of the imprisoned grubs', 'img/achievements/HK/metamorphosis.jpg'),
(30, 12, 'Void', 'Remember the past and unite the Abyss', 'img/achievements/HK/void.jpg'),
(31, 12, 'Worldsoul', 'Acquire all Vessel Fragments', 'img/achievements/HK/worldsoul.jpg'),
(32, 12, 'Witness', 'Spend a final moment with Quirrel', 'img/achievements/HK/witness.jpg'),
(33, 12, 'The Hollow Knight', 'Defeat the Hollow Knight', 'img/achievements/HK/hollow_knight.jpg'),
(34, 12, 'Mortality', 'Defeat the Soul Tyrant', 'img/achievements/HK/mortality.jpg'),
(35, 12, 'Dark Romance', 'Defeat the Grey Prince Zote', 'img/achievements/HK/dark_romance.jpg'),
(36, 12, 'Solace', 'Bring peace to the Grey Mourner', 'img/achievements/HK/solace.jpg'),
(37, 12, 'Fool', 'Complete the Trial of the Fool', 'img/achievements/HK/fool.jpg'),
(38, 12, 'Masked', 'Acquire all Mask Shards', 'img/achievements/HK/masked.jpg'),
(39, 12, 'Ascension', 'Collect 2400 Essence', 'img/achievements/HK/ascension.jpg'),
(40, 12, 'Blessed', 'Acquire all Charms', 'img/achievements/HK/blessed.jpg'),
(41, 12, 'Dream No More', 'Defeat the Radiance', 'img/achievements/HK/dream_no_more.jpg'),
(42, 12, 'Completion', 'Achieve 100% game completion', 'img/achievements/HK/completion.jpg'),
(43, 12, 'Purity', 'Kill the Nailsmith with the Pure Nail', 'img/achievements/HK/purity.jpg'),
(44, 12, 'Sealed Siblings', 'Complete the game with Hornet by your side', 'img/achievements/HK/sealed_siblings.jpg'),
(45, 12, 'Memory', 'Defeat the White Defender', 'img/achievements/HK/memory.jpg'),
(46, 12, 'Brotherhood', 'Defeat the Brothers Oro & Mato', 'img/achievements/HK/brotherhood.jpg'),
(47, 12, 'Inspiration', 'Defeat the Paintmaster Sheo', 'img/achievements/HK/inspiration.jpg'),
(48, 12, 'Ritual', 'Defeat Nightmare King Grimm', 'img/achievements/HK/ritual.jpg'),
(49, 12, 'Focus', 'Defeat the Great Sage Sly', 'img/achievements/HK/focus.jpg'),
(50, 12, 'Happy Couple', 'Allow the Nailsmith to find love', 'img/achievements/HK/happy_couple.jpg'),
(51, 12, 'Soul & Shade', 'Complete the Pantheon of the Knight', 'img/achievements/HK/soul_shade.jpg'),
(52, 12, 'Banishment', 'Banish the Grimm Troupe from Hallownest', 'img/achievements/HK/banishment.jpg'),
(53, 12, 'Neglect', 'Leave Zote to die', 'img/achievements/HK/neglect.jpg'),
(54, 12, 'Keen Hunter', 'Record all of Hallownest\'s creatures', 'img/achievements/HK/keen_hunter.jpg'),
(55, 12, 'True Hunter', 'Receive the Hunter\'s Mark', 'img/achievements/HK/true_hunter.jpg'),
(56, 12, 'Passing of the Age', 'Help the Herald pass on', 'img/achievements/HK/passing_age.jpg'),
(57, 12, 'Pure Completion', 'Achieve 112% game completion', 'img/achievements/HK/pure_completion.jpg'),
(58, 12, 'Speedrun 1', 'Complete the game in under 10 hours', 'img/achievements/HK/speedrun1.jpg'),
(59, 12, 'SpeedCompletion', 'Achieve 100% completion in under 20 hours', 'img/achievements/HK/speedcompletion.jpg'),
(60, 12, 'Speedrun 2', 'Complete the game in under 5 hours', 'img/achievements/HK/speedrun2.jpg'),
(61, 12, 'Embrace the Void', 'Complete the Pantheon of Hallownest', 'img/achievements/HK/embrace_void.jpg'),
(62, 12, 'Steel Soul', 'Finish the game in Steel Soul mode', 'img/achievements/HK/steel_soul.jpg'),
(63, 12, 'Steel Heart', 'Achieve 100% completion in Steel Soul mode', 'img/achievements/HK/steel_heart.jpg'),
(64, 13, 'Equipped', 'Acquire your first Tool', 'img/achievements/Hollow Knight Silksong/13_Equipped_1764764203_0.jpg'),
(65, 13, 'Bound', 'Bind your first Silk Skill', 'img/achievements/Hollow Knight Silksong/13_Bound_1764764203_1.jpg'),
(66, 13, 'Liberated', 'Defeat the Bell Beast', 'img/achievements/Hollow Knight Silksong/13_Liberated_1764764203_2.jpg'),
(67, 13, 'Granted', 'Grant your first wish', 'img/achievements/Hollow Knight Silksong/13_Granted_1764764203_3.jpg'),
(68, 13, 'Pharloom\'s Welcome', 'Defeat Lace in Deep Docks', 'img/achievements/Hollow Knight Silksong/13_PharloomsWelcome_1764764203_4.jpg'),
(69, 13, 'Servant', 'Defeat Fourth Chorus', 'img/achievements/Hollow Knight Silksong/13_Servant_1764764203_5.jpg'),
(70, 13, 'Claimed', 'Claim your first Crest', 'img/achievements/Hollow Knight Silksong/13_Claimed_1764764203_6.jpg'),
(71, 13, 'Restored', 'Acquire 2 Spool Fragments', 'img/achievements/Hollow Knight Silksong/13_Restored_1764764203_7.jpg'),
(72, 13, 'Fanatic', 'Defeat Widow', 'img/achievements/Hollow Knight Silksong/13_Fanatic_1764764203_8.jpg'),
(73, 13, 'Protected', 'Acquire 4 Mask Shards', 'img/achievements/Hollow Knight Silksong/13_Protected_1764764203_9.jpg'),
(74, 13, 'Judge', 'Defeat the Last Judge', 'img/achievements/Hollow Knight Silksong/13_Judge_1764764203_10.jpg'),
(75, 13, 'Last Dance', 'Defeat the Cogwork Dancers', 'img/achievements/Hollow Knight Silksong/13_LastDance_1764764203_11.jpg'),
(76, 13, 'Keen Hunter', 'Grant Nuu\'s wish', 'img/achievements/Hollow Knight Silksong/13_KeenHunter_1764764203_12.jpg'),
(77, 13, 'Tragedian', 'Defeat Trobbio', 'img/achievements/Hollow Knight Silksong/13_Tragedian_1764764203_13.jpg'),
(78, 13, 'Flea Finder', 'Rescue half of Pharloom\'s lost fleas', 'img/achievements/Hollow Knight Silksong/13_FleaFinder_1764764203_14.jpg'),
(79, 13, 'Harmonious', 'Learn the Citadel\'s Threefold song', 'img/achievements/Hollow Knight Silksong/13_Harmonious_1764764203_15.jpg'),
(80, 13, 'Grey Ghost', '', 'img/achievements/Hollow Knight Silksong/13_GreyGhost_1764764203_16.jpg'),
(81, 13, 'Resident', 'Acquire your own Bellhome', 'img/achievements/Hollow Knight Silksong/13_Resident_1764764203_17.jpg'),
(82, 13, 'White Knight', 'Defeat Lace in the Cradle', 'img/achievements/Hollow Knight Silksong/13_WhiteKnight_1764764203_18.jpg'),
(83, 13, 'Transported', 'Open all of the Citadel\'s Ventrica Stations', 'img/achievements/Hollow Knight Silksong/13_Transported_1764764203_19.jpg'),
(84, 13, 'Connected', 'Open all of Pharloom\'s Bellways', 'img/achievements/Hollow Knight Silksong/Connected_1764765426_0.jpg'),
(85, 13, 'Glutton', 'Satiate the Grand Gourmand', 'img/achievements/Hollow Knight Silksong/Glutton_1764766453_0.jpg'),
(86, 13, 'Trail\'s End', 'Grant Shakra\'s wish', 'img/achievements/Hollow Knight Silksong/TrailsEnd_1764766453_1.jpg'),
(87, 13, 'Heretic', '', 'img/achievements/Hollow Knight Silksong/Heretic_1764766453_2.jpg'),
(88, 13, 'Weaver Queen', '', 'img/achievements/Hollow Knight Silksong/WeaverQueen_1764766453_3.jpg'),
(89, 13, 'Snared Silk', '', 'img/achievements/Hollow Knight Silksong/SnaredSilk_1764766453_4.jpg'),
(90, 13, 'Bonded', '', 'img/achievements/Hollow Knight Silksong/Bonded_1764766453_5.jpg'),
(91, 13, 'Fleafriend', 'Rescue all of Pharloom\'s lost fleas and receive their final gift', 'img/achievements/Hollow Knight Silksong/Fleafriend_1764766453_6.jpg'),
(92, 13, 'Regenerated', 'Acquire all Silk Hearts', 'img/achievements/Hollow Knight Silksong/Regenerated_1764766453_7.jpg'),
(93, 13, 'Tyrant', '', 'img/achievements/Hollow Knight Silksong/Tyrant_1764766453_8.jpg'),
(94, 13, 'Seed', '', 'img/achievements/Hollow Knight Silksong/Seed_1764766453_9.jpg'),
(95, 13, 'Diva', '', 'img/achievements/Hollow Knight Silksong/Diva_1764766453_10.jpg'),
(96, 13, 'Fatal Resolve', '', 'img/achievements/Hollow Knight Silksong/FatalResolve_1764766453_11.jpg'),
(97, 13, 'Remembrance', '', 'img/achievements/Hollow Knight Silksong/Remembrance_1764766453_12.jpg'),
(98, 13, 'Consumed', 'Claim all Crests', 'img/achievements/Hollow Knight Silksong/Consumed_1764766453_13.jpg'),
(99, 13, 'Hero\'s Call', '', 'img/achievements/Hollow Knight Silksong/HerosCall_1764766453_14.jpg'),
(100, 13, 'Woven', 'Bind all Silk Skills', 'img/achievements/Hollow Knight Silksong/Woven_1764766453_15.jpg'),
(101, 13, 'Cartographer', 'Acquire a map of each area', 'img/achievements/Hollow Knight Silksong/Cartographer_1764766453_16.jpg'),
(102, 13, 'Lamenter', '', 'img/achievements/Hollow Knight Silksong/Lamenter_1764766453_17.jpg'),
(103, 13, 'Sister of the Void', '', 'img/achievements/Hollow Knight Silksong/SisteroftheVoid_1764766453_18.jpg'),
(104, 13, 'Entwined', '', 'img/achievements/Hollow Knight Silksong/Entwined_1764766453_19.jpg'),
(105, 13, 'Extended', 'Acquire all Spool Fragments', 'img/achievements/Hollow Knight Silksong/Extended_1764766453_20.jpg'),
(106, 13, 'Masked', 'Acquire all Mask Shards', 'img/achievements/Hollow Knight Silksong/Masked_1764766453_21.jpg'),
(107, 13, 'Arsenal', 'Acquire all Tools', 'img/achievements/Hollow Knight Silksong/Arsenal_1764766453_22.jpg'),
(108, 13, 'Twisted Child', '', 'img/achievements/Hollow Knight Silksong/TwistedChild_1764766453_23.jpg'),
(109, 13, 'Passing of the Age', '', 'img/achievements/Hollow Knight Silksong/PassingoftheAge_1764766453_24.jpg'),
(110, 13, 'Completion', 'Achieve 100% game completion and finish the game', 'img/achievements/Hollow Knight Silksong/Completion_1764766453_25.jpg'),
(111, 13, 'True Hunter', 'Receive the Hunter\'s Memento', 'img/achievements/Hollow Knight Silksong/TrueHunter_1764766453_26.jpg'),
(112, 13, 'Speedrunner', 'Complete the game in under 5 hours', 'img/achievements/Hollow Knight Silksong/Speedrunner_1764766453_27.jpg'),
(113, 13, 'Speed Completion', 'Achieve 100% game completion and finish the game in under 30 hours', 'img/achievements/Hollow Knight Silksong/SpeedCompletion_1764766453_28.jpg'),
(114, 13, 'Steel Soul', 'Finish the game in Steel Soul mode', 'img/achievements/Hollow Knight Silksong/SteelSoul_1764766453_29.jpg'),
(115, 13, 'Steel Heart', 'Achieve 100% game completion and finish the game in Steel Soul mode', 'img/achievements/Hollow Knight Silksong/SteelHeart_1764766453_30.jpg'),
(116, 17, 'Lilac and Gooseberries', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/6078587189483353f06f48d0eefdaaa0791e9e13.jpg'),
(117, 17, 'A Friend in Need', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/07bae88f1ee9b856ddfc1d8e28ae7eedd4bcde95.jpg'),
(118, 17, 'Necromancer', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/652e39d4e750183a390a4e9f2f99018c1b335a20.jpg'),
(119, 17, 'Family Counselor', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/883f82ffa933d6d12da3e8733f340b082a08c325.jpg'),
(120, 17, 'Something More', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/97246533609245ba270e7a8eb39a64d319ce5011.jpg'),
(121, 17, 'Xenonaut', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/46b0b574e6334d81211e0c3db1539d3a76364144.jpg'),
(122, 17, 'The King is Dead', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/53e0820b82445eeafe38f4991b947ed6ef64406d.jpg'),
(123, 17, 'Passed the Trial', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/bc1da5cdbd6e323308f012851cf4c3f6a1439c3f.jpg'),
(124, 17, 'Ran the Gauntlet', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/cba4abe872ecb92a755cbc9cf1ffa708b7aef42e.jpg'),
(125, 17, 'Walked the Path', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/292030/03afa403f58958f72f7540345ba16a3439d1f830.jpg'),
(146, 20, 'Life Of The Party', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/1958d20686690299d4b5bf66212b171310822404.jpg'),
(147, 20, 'Wooden Sword', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/de4aa9e8cc5f3029301aca4071eafb2ed425c4cb.jpg'),
(148, 20, 'Diamond Sword', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/31b6d8651d297af5480c2e0742b8e67191e1ec48.jpg'),
(149, 20, 'Passive Aggressive', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/c33c67de3f0f7f476509e35a2579e038ad479556.jpg'),
(150, 20, 'Break the Spell', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/b46561157372ce199e7e7ad7fa80303448170805.jpg'),
(151, 20, 'Scrappy Scout', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/477aa231c7ae8174b948c469504910ef14ccc6a8.jpg'),
(152, 20, 'Apprentice Adventurer', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/1470a54946c4d655d3b6609d54af462f355c4445.jpg'),
(153, 20, 'Expert Explorer', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/f4450245006cffac9774669d283a31ac01284a9d.jpg'),
(154, 20, 'Fancy That!', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/d0e2210bea865706075f3489aa4e76e73840fd81.jpg'),
(155, 20, 'More For Me', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1672970/d71fe3ce3e614679c6b3b9fd01545371ce35eefa.jpg'),
(156, 21, 'Зі світлом приходить надія', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/9b4b7e3f2d68a29dff2f8a92c9475af87648486c.jpg'),
(157, 21, 'На них почали полювати', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/72bb06dd3ca3256eddbf57b1c7e0237b3d3ea633.jpg'),
(158, 21, 'Зламано', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/4597d56df583871a39b3129630e6c8d53fee9ca7.jpg'),
(159, 21, 'Більше, ніж було можливо пожувати', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/115e309f03a7024a1a6332ec39330a42799cd196.jpg'),
(160, 21, 'Захисник Верхнього світу', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/2937abb7ffdb2f7b8b9f8e971f9d9611c1ee62f3.jpg'),
(161, 21, 'Верхова їзда зі стилем', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/80fbe8482e024847df52a41e664373dd143e2609.jpg'),
(162, 21, 'Різноманітність — це пряність життя', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/e639c38daf4b507ba46f5d99abfae7ef8ff0812f.jpg'),
(163, 21, 'Спільна робота', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/41320ed02fabab94b9904ca04353cfd5470f50c8.jpg'),
(164, 21, 'Звідси я бачу своє село', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/fbb519ac979a01b9ce44d66e9eff23ad6e486022.jpg'),
(165, 21, 'Легендарний герой', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1928870/0083308189895f454d9027210b5b8f0e5877b4a6.jpg'),
(176, 23, 'Піддослідна миша', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_getportalguns.jpg'),
(177, 23, 'Братовбивство', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_kill_companioncube.jpg'),
(178, 23, 'Гуляка', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_escape_testchambers.jpg'),
(179, 23, 'Фатальна жінка', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_beat_game.jpg'),
(180, 23, 'Гранична швидкість', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_infinitefall.jpg'),
(181, 23, 'Стрибок у довжину', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_longjump.jpg'),
(182, 23, 'Кекс', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_beat_2advancedmaps.jpg'),
(183, 23, 'Фруктовий пиріг', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_beat_4advancedmaps.jpg'),
(184, 23, 'Шалений ванільний торт', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_beat_6advancedmaps.jpg'),
(185, 23, 'Основи кінематики', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/400/portal_get_allbronze.jpg'),
(186, 24, 'Like Taking Candy from a Baby', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/b5745fb05a529e63276a2482ffa70351e493149c.jpg'),
(187, 24, 'Not in the Mood to Die', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/7c786c2e27a0890c60ee802a3af93199312c1a7f.jpg'),
(188, 24, 'Take that, Zombies!', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/82b80fe710fc9b0bc82f5f5b12b7474faf8e02be.jpg'),
(189, 24, 'Oh, the Horror', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/b0c2ebf083c23fe37af49ae098bb18e01ffed675.jpg'),
(190, 24, 'We\'re in This Together', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/51b5c2aa3991739686352a159f80a80052b80435.jpg'),
(191, 24, 'The Survival Horror', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/88688c9dcc9cc0ac6048cc78916b85539c8b02e3.jpg'),
(192, 24, 'Ghost of a Chance', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/9fc10ce8640d2df866a843c9abbb0a6ca09ada29.jpg'),
(193, 24, 'Not Just Any Object', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/5bcd17ac34622fd8507d8c7b26d662729375b704.jpg'),
(194, 24, 'Herbicide', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/2415f8e05715262e80c09e0d868ded0639777056.jpg'),
(195, 24, 'Written Word is Dead', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/304240/9b0a4366d92c2c7a1d6f1ba826edccb8cc4a71c1.jpg'),
(196, 25, 'Déjà vu', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/b348dd6703d7634fad4cba22f3728ea6db8d0dd8.jpg'),
(197, 25, 'Descent Into Darkness', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/50a45d1969baeea1a594fc6f80a515ce71ebc1fa.jpg'),
(198, 25, 'It\'s Been a Long Night', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/81cca1fee65a414a3078358e381ba39935a12aa2.jpg'),
(199, 25, 'A Harsh Reality', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/4b9821bd83adbdba47b9871bec11fed86a24d99b.jpg'),
(200, 25, 'Going Down?', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/4742edabc6da9c22dcfbe3b8eba2593d14253f88.jpg'),
(201, 25, 'The Hero Returns', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/862bf225eb5d2be8f630f6bab58a38636f7f087d.jpg'),
(202, 25, 'I Remember That, Too', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/36d6947818c8fe910f797227ce4d78a9c7f66a99.jpg'),
(203, 25, 'Umbrella\'s Legacy', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/fb7097c54af91995a06b2e945adc28a73426de19.jpg'),
(204, 25, 'The Final Mission', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/b70b17addb883238a4c8655a1ef934505cefe6d2.jpg'),
(205, 25, 'Hope and Requiem', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/3764200/38320c1c46265dc355e51c551801aefdc385ee1a.jpg'),
(206, 27, 'The Dark Soul', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/1fcdb90f670bc6b072b66a753102cf59ec7acaff.jpg'),
(207, 27, 'To Link the First Flame', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/e46db10c11872c0d33afd3a897641e8c025f2ece.jpg'),
(208, 27, 'The End of Fire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/16f8a5dfef5078c95cb035dd5f2c7827e0e6ae2a.jpg'),
(209, 27, 'The Usurpation of Fire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/7c6b3b6f17e6010e58941b9bdc8fa8df68114037.jpg'),
(210, 27, 'Lords of Cinder: Abyss Watchers', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/1b99140392239cf07d9fff2cb34d4225fc05b919.jpg'),
(211, 27, 'Lord of Cinder: Yhorm the Giant', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/337601fee6c10d1b3cc722a6b303f13b053190a3.jpg'),
(212, 27, 'Lord of Cinder: Aldrich, Devourer of Gods', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/3c5c4b79ee8dbea8d495a6fbc1a88b12ee51c977.jpg'),
(213, 27, 'Lord of Cinder: Lothric, Younger Prince', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/c94a1880f38845669c1ef64a9d546f5b51506694.jpg'),
(214, 27, 'Supreme Weapon Reinforcement', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/eaa505e96796b516e184c1afb17084e734f85485.jpg'),
(215, 27, 'Master of Infusion', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/374320/d1ad58686eb4f5073f5666a9b5fe17dd5183dc38.jpg'),
(216, 28, 'The Dark Soul', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/22067071ea9eeb92edb952b2d65abce16e973fc7.jpg'),
(217, 28, 'To Link the Fire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/fde13ddfbeb188d4e83ca5e581deb69dea09c615.jpg'),
(218, 28, 'Dark Lord', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/638c52abf0711ffcabc273473dd247b071cd3734.jpg'),
(219, 28, 'Knight\'s Honor', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/62149815daa726018b21bcabdf215c593f0b3d7b.jpg'),
(220, 28, 'Wisdom of a Sage', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/cecb76c38356f44712e304ef2463e55e7a0dcf1e.jpg'),
(221, 28, 'Bond of a Pyromancer', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/3ba9afc8fbfeaaafcd19cbb09f30a63d164e5412.jpg'),
(222, 28, 'Prayer of a Maiden', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/2ab79f60b52940c674b2c18782c67a7ccdd623e0.jpg'),
(223, 28, 'Covenant: Way of White', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/c028fb1af748399ebf2888267634e22d87e8632e.jpg'),
(224, 28, 'Covenant: Princess\'s Guard', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/c250e7b039ead972bec8ff3bbbc5f4dd13292e2a.jpg'),
(225, 28, 'Covenant: Blade of the Darkmoon', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/570940/8ff13be8b0113347950fdcad91ff764db8f6e02f.jpg'),
(226, 29, 'The Dark Soul', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/461a5f9cc63d50b631724e3d3146ce98c6a84303.jpg'),
(227, 29, 'Self Recollection', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/ec79859c45d40951c8c5e08790bbf2232112cd27.jpg'),
(228, 29, 'King\'s Ring', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/21f1a21c6ec5107e3106c7a16a03daf28ced5495.jpg'),
(229, 29, 'Ancient Dragon', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/9a65cfc7728e56bb2e288f1f86b7b4cd1446cae8.jpg'),
(230, 29, 'The Heir', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/c8c582769a5249bdf670b246dc0737b4f3a16d9d.jpg'),
(231, 29, 'Last Giant', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/bc22c45dbafc988dd057e6429ea90c92bb95ce87.jpg'),
(232, 29, 'Sinner\'s Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/71f49f1d53b09201b4c0ff45fedf7f38543649fe.jpg'),
(233, 29, 'Iron Keep Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/cb2b7164dc32b0f5033e3017badc9f8b6fcf3051.jpg'),
(234, 29, 'Gulch Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/a764363dfb38f25f7abbdbf0613605bb3de06d5f.jpg'),
(235, 29, 'Brightstone Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/335300/d0888ff14e2efeef7152865832471e06c8dfd516.jpg'),
(236, 30, 'The Dark Soul', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/9679384a4ad573ec4c1ccb549a1528b1926d6040.jpg'),
(237, 30, 'Self Recollection', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/a44b9e31b85cab826f76b3d13dd98981b380d9f7.jpg'),
(238, 30, 'King\'s Ring', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/5db726786191c4eb4721da684b2e86a19c527254.jpg'),
(239, 30, 'Ancient Dragon', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/2d24775f6fa06a41686546c378c5d7fce763e2e2.jpg'),
(240, 30, 'The Heir', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/bb494120f6a612f6d1abcf54c2c8d70bdc26a4d2.jpg'),
(241, 30, 'Last Giant', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/9f3129c23bab8afe74e931ab1c75e0d47b15cc68.jpg'),
(242, 30, 'Sinner\'s Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/9fc6174d3c6fd5391642e8dd78898dcb259cac5b.jpg'),
(243, 30, 'Iron Keep Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/b0ca04bab6cfd0e27f57042c3e680371e55e638a.jpg'),
(244, 30, 'Gulch Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/797ad97096eda6136b04db431cf5cf122d0f6808.jpg'),
(245, 30, 'Brightstone Bonfire', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/236430/fd714fa281dba96bb1d6fcc794189bbc2439939c.jpg'),
(246, 31, 'Sekiro', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/5b4b096ea3b03a5d9cb1367e53d85dd4a4300dc8.jpg'),
(247, 31, 'Man Without Equal', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/99a3f76eb4e25f821826575f361a0b8ec4de718e.jpg'),
(248, 31, 'Ashina Traveler', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/3ff93d2915cfc175d391eeebc1780c68d0054b98.jpg'),
(249, 31, 'Master of the Prosthetic', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/183b69eaa3f8c8901a41c05959c9793f51e7d6dd.jpg'),
(250, 31, 'Height of Technique', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/81b2ef0f9c0a93ca1bcfc2ef3ad67a7001daaa2a.jpg'),
(251, 31, 'All Prosthetic Tools', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/e34d6e8365208a04c04ad63977c1ce39a26afe64.jpg'),
(252, 31, 'All Ninjutsu Techniques', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/b8ca679e7fc6df38774f5c34263b15ace8962989.jpg'),
(253, 31, 'Peak Physical Strength', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/5789e9de2d292b9879077751ccc2a49144fdf3b3.jpg'),
(254, 31, 'Ultimate Healing Gourd', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/923bc3ee8b204785cde4f5e28b659d92e62be97e.jpg'),
(255, 31, 'Immortal Severance', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/814380/4185b74c93880d4a7f4cb2cbf5da43a6d5fb78c2.jpg'),
(256, 32, 'Let\'s Partyyyyy!!!', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/c5dc6a92371119f4525e43dd9fc7065514d13c83.jpg'),
(257, 32, 'The One Great Sight', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/3e2032b20824f78e0a1bbfa25c20220594095376.jpg'),
(258, 32, 'Smash the Island Prison', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/bc9c2df2eadda18b8395577114bf782fbae33db2.jpg'),
(259, 32, 'Beacon of hope in your counteroffensive', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/700bebe3d7de78c13bdb8e3860e19fc1918c5bb0.jpg'),
(260, 32, 'Eagle of the Atlantic', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/36878595f7914611b75829719eec36197f8c24e0.jpg'),
(261, 32, 'My Darling Clementine', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/758fdaf0f6ea968464ed8b2c85756cabe16819b4.jpg'),
(262, 32, 'Chicago Daybreak', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/746f9186b52a94e2b1fd990517d59c8adc768daf.jpg'),
(263, 32, 'Aerial fortress', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/6477c144c8efbac0b815bb7ce835eb3976f3eb62.jpg'),
(264, 32, 'All night show', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/e243bb12499af8ce4e203ffc0861cf98754f37be.jpg'),
(265, 32, 'Freedom and justice in your hands', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/820630/2302cbd52ef78e2d9bde993e6be42448f2075d15.jpg'),
(266, 33, 'Elden Ring', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/1cb1fe89084e822bd7d9ad4df66bc7f917995b38.jpg'),
(267, 33, 'Elden Lord', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/e50726be4aca4d2b1f5f14d36859fc8e6f710097.jpg'),
(268, 33, 'Age of the Stars', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/7e7369bceba13739025766a72560fe9ce7ac56c9.jpg'),
(269, 33, 'Lord of Frenzied Flame', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/230fc53f4bc2d9bf39be031cc198be81508a9c47.jpg'),
(270, 33, 'Shardbearer Godrick', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/1e230f1a87d7139e854c47e52337f9d50856ac64.jpg'),
(271, 33, 'Shardbearer Radahn', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/f5f1f41ef749459d9ac45750cd1f069d05fe1dd8.jpg'),
(272, 33, 'Shardbearer Morgott', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/1ffc295a2cd48b88487d410025d081549d932b27.jpg'),
(273, 33, 'Shardbearer Rykard', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/cb0aec39fcc664c4e586750177178b6814a52e0d.jpg'),
(274, 33, 'Shardbearer Malenia', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/f704fcd82daf933dd3ce81c4d8ffea3ec65f26f4.jpg'),
(275, 33, 'Shardbearer Mohg', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1245620/a00f99e4944d09603d64996c5ade85264bcba22d.jpg'),
(276, 34, 'Nightreign', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/17f60812241f852530650bc209d8462f992f97ae.jpg'),
(277, 34, 'The Shrouded Roundtable Hold', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/768da8bec8e6cf31d5fb0ac90523b10e22056b82.jpg'),
(278, 34, 'The Nightlords', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/3fa377facbb904ca9acefb39394b795f90bc0f9f.jpg'),
(279, 34, 'Night Begins', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/562bc40bff2bc10f64494e3ef26bddfb721e1086.jpg'),
(280, 34, 'The Duchess Joins the Fray', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/0d1976cd49fd7a219561221e593e87d5ac565825.jpg'),
(281, 34, 'The Revenant Joins the Fray', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/33b251627a3e20b0b793147bdb1b0ad37760ba5b.jpg'),
(282, 34, 'Dawn', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/0635a6533b5b800be0446068f8001743db3fc8d0.jpg'),
(283, 34, 'Tricephalos', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/0ff49c49ba63f7f8c74e504a9260a8e3af5d2d1b.jpg'),
(284, 34, 'Gaping Jaw', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/fbbf92201a5dc9c054e4ca415b0770fde2429ff4.jpg'),
(285, 34, 'Sentient Pest', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/2622380/ae10691bca16f49df8d022c8adc0160c53c302f1.jpg'),
(286, 35, 'Armored Core', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/c4a72ffdee84f901c71f304f9d65948e1e328687.jpg'),
(287, 35, 'The Perfect Mercenary', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/2b66a54a2f78189ef2d447d17e2ece5796230606.jpg'),
(288, 35, 'Stargazer', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/c7a3a2ca0428d9c68ddef38521d4808b60ad9f1d.jpg'),
(289, 35, 'Master of Arena', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/684780ec478047e65ad747ecb291c43139a1bb52.jpg'),
(290, 35, 'Asset Holder', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/6cb78ae7086501d8f9377f35f05fa93966ae08bd.jpg'),
(291, 35, 'Tuning Expert', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/32e78d9936aa42fa8cef38bec2bcedaa13a572f4.jpg'),
(292, 35, 'The Fires of Raven', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/82ff3407098e22c4065fd42b242e10d0a5ea412d.jpg'),
(293, 35, 'Liberator of Rubicon', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/9a132b914f40a7eae06932ec84f6a6ed33e709a3.jpg'),
(294, 35, 'Alea Iacta Est', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/c6eb4225226ab2b07c1238998bb61ae363a82d97.jpg'),
(295, 35, 'Weapon Collector', 'Офіційне досягнення Steam', 'https://cdn.akamai.steamstatic.com/steamcommunity/public/images/apps/1888160/c468005de8079d808f5ca0428c277ab5b5c13330.jpg'),
(306, 38, 'Есенція старого мисливця', 'Отримайте всю стару мисливську зброю.', 'https://media.rawg.io/media/achievements/ac6/ac690cde89857960b14c9d749ea1f7ad.jpg'),
(307, 38, 'Bloodborne', 'Усі трофеї отримано. Знімаю капелюхи!', 'https://media.rawg.io/media/achievements/3cb/3cbdee83c86ee4e1c7bba0537fe77a94.jpg'),
(308, 38, 'Мисливська сутність', 'Отримайте всю мисливську зброю.', 'https://media.rawg.io/media/achievements/851/85109b52355a68ad37dbf0f117d73736.jpg'),
(309, 38, 'Ремесло мисливця', 'Придбайте всі спеціальні інструменти мисливця.', 'https://media.rawg.io/media/achievements/580/58007ff8b09cb217603e28d6c9ffabd7.jpg'),
(310, 38, 'Майстер самоцвітів крові', 'Отримайте надзвичайно дорогоцінний камінь крові.', 'https://media.rawg.io/media/achievements/d5f/d5ff75b858881733a1e6b7f10f677eb8.jpg'),
(311, 38, 'Майстер зброї', 'Отримайте зброю найвищого рівня.', 'https://media.rawg.io/media/achievements/bfd/bfda9dc257650e8c226becd46b991a5d.jpg'),
(312, 38, 'Майстер рун', 'Отримайте надзвичайно дорогоцінну руну Карилла.', 'https://media.rawg.io/media/achievements/adc/adc8d2d122215ed96171c9046004f7ec.jpg'),
(313, 38, 'Чаша Птумеру', 'Отримайте чашу Птумеру, яка запечатує катакомби\r\nякі утворюють мережу глибоко під Ярнамом.', 'https://media.rawg.io/media/achievements/3a1/3a1d52e2d4a6c1152c890d6a57b0457c.jpg'),
(314, 38, 'Руна Контакт', 'Отримайте руну Карилла, яка наділяє мисливців особливою силою.', 'https://media.rawg.io/media/achievements/68b/68bdc416616b38cb01262f4616947e10.jpg'),
(315, 38, 'Контакт з самоцвітом крові', 'Отримайте дорогоцінний камінь крові, який наповнює мисливську зброю\r\nз особливою силою.', 'https://media.rawg.io/media/achievements/c53/c53c17b8754f451d92bb528f945089f7.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `developer_reviews`
--

CREATE TABLE `developer_reviews` (
  `id` int NOT NULL,
  `developer_user_id` int NOT NULL,
  `author_user_id` int NOT NULL,
  `comment_text` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `followers`
--

CREATE TABLE `followers` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `developer_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `developer_id`, `created_at`) VALUES
(1, 15, 27, '2026-03-23 10:16:27'),
(2, 15, 25, '2026-03-23 17:53:18'),
(3, 15, 24, '2026-03-23 17:53:22'),
(4, 15, 3, '2026-03-23 17:53:26'),
(5, 15, 29, '2026-03-23 21:39:36');

-- --------------------------------------------------------

--
-- Структура таблицы `friendships`
--

CREATE TABLE `friendships` (
  `id` int NOT NULL,
  `user_id1` int NOT NULL,
  `user_id2` int NOT NULL,
  `status` enum('pending','accepted') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `friendships`
--

INSERT INTO `friendships` (`id`, `user_id1`, `user_id2`, `status`, `created_at`) VALUES
(1, 15, 14, 'accepted', '2026-03-12 10:22:32'),
(3, 14, 16, 'accepted', '2026-03-23 14:35:59'),
(4, 16, 15, 'accepted', '2026-03-23 15:21:30');

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `features` text COLLATE utf8mb4_general_ci,
  `languages` text COLLATE utf8mb4_general_ci,
  `cover_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tags` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `developer` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `publisher_id` int DEFAULT NULL,
  `is_in_hero_slider` tinyint(1) NOT NULL DEFAULT '0',
  `release_date` date NOT NULL,
  `sys_min` text COLLATE utf8mb4_general_ci NOT NULL,
  `sys_rec` text COLLATE utf8mb4_general_ci NOT NULL,
  `screenshot1` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `screenshot2` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `screenshot3` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `screenshot4` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `screenshot5` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`id`, `title`, `description`, `features`, `languages`, `cover_url`, `tags`, `developer`, `publisher`, `publisher_id`, `is_in_hero_slider`, `release_date`, `sys_min`, `sys_rec`, `screenshot1`, `screenshot2`, `screenshot3`, `screenshot4`, `screenshot5`, `is_approved`) VALUES
(1, 'Restaurats', 'Навіть якщо ти щур — стиль понад усе! З сотнями варіантів кастомізації створюй найшаленішого, найстильнішого або найефектнішого щурячого шефа, якого тільки мріяв.\n\nТак, ти почув правильно. Кожна гра — це нова хаотична кулінарна пригода, повна божевільних страв, несподіваних поворотів і шалених босів!\n\nБери хлібну зброю і покажи, хто тут шеф — або ввічливо виведи буйних гостей… якщо треба, то й силою!\n\nЧас спливає, а гості голодні! Упустив їжу на підлогу? Пусте — ми ж щурі, чого ти чекав?', NULL, NULL, 'img/Game/Restaurats.avif', 'Симулятор, Кооператив, Казуальна', 'PlateUp Team', 'Indie Devs', 1, 0, '2025-10-01', 'ОС: Windows 10\r\nПроцесор: Intel Core i3\r\nПам\'ять: 4 GB RAM\r\nВідеокарта: NVIDIA GeForce GTX 560\r\nМісце: 2 GB', 'ОС: Windows 10\r\nПроцесор: Intel Core i5\r\nПам\'ять: 8 GB RAM\r\nВідеокарта: NVIDIA GeForce GTX 970\r\nМісце: 2 GB', 'img/Game/Restaurats/sc1.jpg', 'img/Game/Restaurats/sc2.jpg', 'img/Game/Restaurats/sc3.jpg', 'img/Game/Restaurats/sc4.jpg', 'img/Game/Restaurats/sc5.jpg', 1),
(2, 'My Little Puppy', 'Бон-гу, вельш-коргі, колись був бездомним собакою. Судячи з його обрізаного хвоста, колись хтось дбав про нього, але з якоїсь причини він став небажаним. Як 8-річному собаці-ветерану з різними захворюваннями, йому було важко знайти нову сім\'ю. Замкнений у клітці в притулку для тварин, кожен день для Бон-гу проходив похмуро.\n\nАж одного дня сталося диво. Бонг-гу зустрів нову сім\'ю. Чоловік, який взяв Бонг-гу, мав дещо неохайний вигляд і здавався трохи незграбним, але Бонг-гу полюбив його запах. З того дня Бонг-гу прожив свої останні дні, як і будь-який інший собака: граючи в перетягування, бігаючи, їдячи та сплячи. Врешті-решт він перейшов райдужний міст, проживши просте, але радісне життя.', NULL, NULL, 'img/Game/My Little Puppy.jpg', 'Симулятор, Пригоди, Казуальна', 'Cute Inc.', 'Cute Inc.', 1, 0, '2025-11-05', 'ОС: Windows 10\r\nПроцесор: Intel Core 2 Duo\r\nПам\'ять: 2 GB RAM\r\nВідеокарта: Intel HD Graphics\r\nМісце: 1 GB', 'ОС: Windows 11\r\nПроцесор: Intel Core i3\r\nПам\'ять: 4 GB RAM\r\nВідеокарта: NVIDIA GeForce GT 1030\r\nМісце: 1 GB', 'img/Game/My Little Puppy/sc1.jpg', 'img/Game/My Little Puppy/sc2.jpg', 'img/Game/My Little Puppy/sc3.jpg', 'img/Game/My Little Puppy/sc4.jpg', 'img/Game/My Little Puppy/sc5.jpg', 1),
(3, 'Sanatorium - A Mental Asylum', '«Sanatorium: A Mental Asylum Simulator — одна з найоригінальніших ігор, які я коли-небудь бачив» — VICE\n\nЦе бурхливі двадцяті роки, епоха технологічного прогресу та соціальних потрясінь. Місто навколо вас розростається, а ви відчуваєте себе покинутим: ви — втомлений журналіст, обтяжений боргами та втратою сенсу життя. І раптом зненацька від друга дитинства надходить загадкове повідомлення — можливо, найважливіше в житті. Воно вказує на сумнозвісний санаторій «Касл Вудс». Там на вас чекає таємниця, і ви не можете позбутися бажання її розкрити. Можливо, це лише передчуття, але здається, що ця таємниця може змінити все. Навіть вас. Рішення прийнято: ви пакуєте валізи і вирушаєте до «Касл Вудс».', NULL, NULL, 'img/Game/Sanatorium - A Mental Asylum.jpg', 'Хоррор, Симулятор, Детектив', 'Horror Games UA', 'Horror Games UA', 1, 0, '2025-09-15', 'ОС: Windows 10 (64-bit)\r\nПроцесор: Intel Core i5-4460\r\nПам\'ять: 8 GB RAM\r\nВідеокарта: NVIDIA GeForce GTX 760\r\nМісце: 10 GB', 'ОС: Windows 10 (64-bit)\r\nПроцесор: Intel Core i7-3770\r\nПам\'ять: 16 GB RAM\r\nВідеокарта: NVIDIA GeForce GTX 1060\r\nМісце: 10 GB', 'img/Game/Sanatorium - A Mental Asylum/sc1.jpg', 'img/Game/Sanatorium - A Mental Asylum/sc2.jpg', 'img/Game/Sanatorium - A Mental Asylum/sc3.jpg', 'img/Game/Sanatorium - A Mental Asylum/sc4.jpg', 'img/Game/Sanatorium - A Mental Asylum/sc5.jpg', 1),
(4, 'Star Ores Inc.', 'Ласкаво просимо до Star Ores Inc. – де руда, роботи та жага до грошей визначають ваш успіх! Ваша кар\'єра починається на покинутій космічній станції посеред космосу. \n\nПроривайтеся крізь астероїд, щоб видобувати цінні мінерали.\nПереробляйте мінерали на рідкісні, дорогі продукти.\n\nВикористовуйте спеціалізованих роботів, машини та транспортні системи, щоб вдосконалити видобуток, транспортування та торгівлю.\n\nПродавайте свої знахідки клієнтам з усіх куточків галактики та накопичуйте неймовірні багатства.\n\nЗнищуйте інопланетні нарости, відкривайте приховані сектори, реактивуйте термінали та модернізуйте свій лазер, щоб робити ще більші знахідки.\n\nВізьміть свій лазер, поверніть космічну станцію до життя і станьте легендою міжгалактичного видобутку корисних копалин. Ваша кар\'єра починається ЗАРАЗ!', NULL, NULL, 'img/Game/Star Ores Inc..jpg', 'Стратегія, Симулятор, Фантастика', 'Factorio Devs', 'Indie Labs', 1, 0, '2026-01-20', 'ОС: Windows 10\r\nПроцесор: Dual core 3.0Ghz\r\nПам\'ять: 8 GB RAM\r\nВідеокарта: 2GB VRAM\r\nМісце: 5 GB', 'ОС: Windows 11\r\nПроцесор: Quad core 3.5Ghz\r\nПам\'ять: 16 GB RAM\r\nВідеокарта: 4GB VRAM\r\nМісце: 5 GB', 'img/Game/Star Ores Inc/sc1.jpg', 'img/Game/Star Ores Inc/sc2.jpg', 'img/Game/Star Ores Inc/sc3.jpg', 'img/Game/Star Ores Inc/sc4.jpg', 'img/Game/Star Ores Inc/sc5.jpg', 1),
(5, 'Outlast', 'Пекло — це експеримент, в якому ви не зможете вижити в Outlast, грі жахів від першої особи, розробленій ветеранами деяких з найбільших ігрових франшиз в історії. У ролі журналіста-розслідувача Майлза Апшура досліджуйте психіатричну лікарню Маунт Массів і спробуйте вижити достатньо довго, щоб розкрити її жахливу таємницю... якщо ви наважитеся.\n\nСинопсис:\n   У віддалених горах Колорадо жахи чекають всередині психіатричної лікарні Маунт Массив. Давно покинутий притулок для психічно хворих, нещодавно відновлений відділом «досліджень і благодійності» транснаціональної корпорації Муркофф, лікарня працювала в суворій таємниці... дотепер.\n   Діючи за підказкою анонімного джерела, незалежний журналіст Майлз Апшур проникає в заклад і виявляє щось жахливе, що балансує на межі науки і релігії, природи і чогось зовсім іншого. Опинившись всередині, його єдина надія на втечу — жахлива правда, що криється в серці Маунт Массив.\n   Outlast — це справжній survival horror, який прагне показати, що найжахливіші монстри походять з людського розуму.\n', NULL, NULL, 'img/Game/Outlast.jpg', 'Хоррор, Виживання, Інді, Екшн', 'Red Barrels', 'Red Barrels', 3, 0, '2012-09-04', 'ОС *: Windows XP / Vista / 7 / 8 - 64 bits *\r\nПроцесор: 2.2 GHz Dual Core CPU\r\nОперативна пам’ять: 2 GB ОП\r\nВідеокарта: 512 MB NVIDIA GeForce 9800GTX / ATI Radeon HD 3xxx series\r\nDirectX: версії 9.0c\r\nМережа: широкосмугове підключення до Інтернету\r\nМісце на диску: 5 GB доступного місця\r\nЗвукова карта: DirectX Compatible', 'ОС *: Windows Vista / 7 / 8 - 64 bits\r\nПроцесор: 2.8 GHz Quad Core CPU\r\nОперативна пам’ять: 3 GB ОП\r\nВідеокарта: 1GB NVIDIA GTX 460 / ATI Radeon HD 6850 or better\r\nDirectX: версії 9.0c\r\nМережа: широкосмугове підключення до Інтернету\r\nМісце на диску: 5 GB доступного місця\r\nЗвукова карта: DirectX Compatible', 'img/Game/Outlast/sc1.jpg', 'img/Game/Outlast/sc2.jpg', 'img/Game/Outlast/sc3.jpg', 'img/Game/Outlast/sc4.jpg', 'img/Game/Outlast/sc5.jpg', 1),
(6, 'Outlast 2', 'Outlast 2 — це продовження відомої гри жахів Outlast. Дія Outlast 2 відбувається в тому самому всесвіті, що й перша гра, але з іншими персонажами та в іншому оточенні. Це нова похмура подорож у глибини людської свідомості та її темних таємниць.\r\n\r\n   Outlast 2 знайомить вас із Салліваном Нотом та його послідовниками, які залишили наш грішний світ, щоб заснувати Темпл-Гейт — місто, розташоване глибоко в пустелі та приховане від цивілізації. Нот і його паства готуються до випробувань кінця світу, і ви опиняєтеся в самому епіцентрі подій.\r\n\r\n   Ви — Блейк Лангерманн, оператор, який працює разом зі своєю дружиною Лінн. Ви обоє — журналісти-розслідувачі, готові ризикувати і копати глибоко, щоб розкрити історії, до яких ніхто інший не наважиться доторкнутися.\r\n\r\n   Ви слідуєте за ниткою доказів, яка почалася з, здавалося б, неможливого вбивства вагітної жінки, відомої лише як Джейн Доу.\r\nРозслідування привело вас за багато миль в пустелю Арізони, в таку глибоку темряву, що ніхто не може пролити на неї світло, і в таку глибоку корупцію, що єдиним розумним рішенням може бути божевілля.', NULL, NULL, 'img/Game/Outlast-2.jpg', 'Хоррор, Виживання, Інді, Екшн', 'Red Barrels', 'Red Barrels', 3, 0, '2017-04-25', 'ОС *: Windows Vista / 7 / 8 / 10, 64-bits\r\nПроцесор: Intel Core i3-530\r\nОперативна пам’ять: 4 GB ОП\r\nВідеокарта: 1GB VRAM, NVIDIA Geforce GTX 260 / ATI Radeon HD 4870\r\nDirectX: версії 10\r\nМісце на диску: 30 GB доступного місця\r\nЗвукова карта: DirectX Compatible\r\nДодаткові примітки: Targetting 720p @ 30 fps', 'ОС *: Windows Vista / 7 / 8 / 10, 64-bits\r\nПроцесор: Intel Core i5\r\nОперативна пам’ять: 8 GB ОП\r\nВідеокарта: 1.5GB VRAM, NVIDIA Geforce GTX 660 / ATI Radeon HD 7850\r\nDirectX: версії 11\r\nМісце на диску: 30 GB доступного місця\r\nЗвукова карта: DirectX Compatible\r\nДодаткові примітки: Targetting 1080p @ 60 fps', 'img/Game/Outlast-2/sc1.jpg', 'img/Game/Outlast-2/sc2.jpg', 'img/Game/Outlast-2/sc3.jpg', 'img/Game/Outlast-2/sc4.jpg', 'img/Game/Outlast-2/sc5.jpg', 1),
(7, 'Counter-Strike 2', 'Упродовж більш ніж двох десятиліть Counter-Strike пропонував елітний змагальний досвід, сформований мільйонами гравців з усього світу. І тепер починається новий розділ в історії CS: Counter-Strike 2.\r\n\r\nCounter-Strike 2 — це безкоштовне оновлення до CS:GO, яке позначає найбільший технічний стрибок в історії Counter-Strike. Гра створена на рушії Source 2 з сучасною реалістичною та фізично правдоподібною візуалізацією, передовою мережевою технологією та покращеними інструментами майстерні для спільноти.\r\n\r\nОкрім класичного ігроладу, впровадженого ще 1999 року й зорієнтованого на досягнення цілей, Counter-Strike 2 містить:\r\n\r\n- цілковито новий рейтинг CS та оновлений прем’єр-режим;\r\n- глобальні й регіональні таблиці лідерів;\r\n- покращені й перероблені мапи;\r\n- гнучкий дим від димових гранат, який міняє підхід до гри;\r\n- ігролад, що не залежить від частоти тіків;\r\n- осучаснені візуальні ефекти і звуки;\r\n- усі ваші предмети з CS:GO.', NULL, NULL, 'img/Game/Counter-Strike-2.jpg', 'Шутер, Багатокористувацька, Екшн, Безкоштовна', 'Valve', 'Valve', 24, 0, '2012-08-21', 'ОС: Windows® 10\r\nПроцесор: 4-потоковий процесор — Intel® Core™ i5 750 або краще\r\nОперативна пам’ять: 8 GB ОП\r\nВідеокарта: 1 ГБ пам’яті або більше, сумісна з DirectX 11 і з підтримкою Pixel Shader 5.0\r\nDirectX: версії 11\r\nМісце на диску: 85 GB доступного місця', 'ОС: Windows® 10\r\nПроцесор: 4-потоковий процесор — Intel® Core™ i5 750 або краще\r\nОперативна пам’ять: 8 GB ОП\r\nВідеокарта: 1 ГБ пам’яті або більше, сумісна з DirectX 11 і з підтримкою Pixel Shader 5.0\r\nDirectX: версії 11\r\nМісце на диску: 85 GB доступного місця', 'img/Game/Counter-Strike-2/sc1.jpg', 'img/Game/Counter-Strike-2/sc2.jpg', 'img/Game/Counter-Strike-2/sc3.jpg', 'img/Game/Counter-Strike-2/sc4.jpg', 'img/Game/Counter-Strike-2/sc5.jpg', 1),
(8, 'Dota 2', 'Найпопулярніша гра в Steam.\r\nЩодня мільйони гравців у всьому світі вступають у бій за одного з понад сотні героїв Dota. І немає значення, буде це 10-та година гри чи 1000-на, завжди знайдеться щось нове для відкриття. Завдяки регулярним оновленням, які забезпечують постійну еволюцію ігроладу, можливостей та героїв, Dota 2 дійсно розпочала жити власним життям.\r\n\r\nОдне поле бою. Безмежні можливості.\r\nКоли справа доходить до різноманітності героїв, здібностей та потужних предметів, Dota має безкінечну кількість варіантів — немає двох однакових ігор. Будь-який герой може виконувати безліч ролей, а велика кількість предметів допоможе задовольнити всі потреби гри. Dota не надає обмежень на те, як грати — ви можете створити свій власний стиль гри.\r\n\r\nУсі герої безкоштовні.\r\nЗмагальна рівновага — головна перлина Dota, і для того, щоби всі мали рівні можливості, основний вміст гри — як-от величезний вибір героїв — доступний усім гравцям. Шанувальники можуть купувати декоративні предмети для персонажів та веселі доповнення для їхнього світу, але все необхідне для гри включено ще до того, як ви приєднаєтеся до першого матчу.\r\n\r\nПриводьте своїх друзів та грайте разом.\r\nDota різнобічна, і постійно розвивається, проте приєднатися ніколи не пізно.\r\nВивчайте основи гри в кооперативі з ботами. Відточуйте здібності в деморежимі героя. Пориньте в систему пошуку матчів, побудовану на поведінці та здібностях, котра гарантує, що для вас будуть підібрані правильні гравці в кожному матчі.', NULL, NULL, 'img/Game/Dota-2.jpg', 'Стратегія, Багатокористувацька, Безкоштовна', 'Valve', 'Valve', 24, 0, '2013-07-09', 'ОС *: Windows 7 чи новішої версії\r\nПроцесор: двоядерний процесор Intel або AMD з частотою 2,8 ГГц\r\nОперативна пам’ять: 4 GB ОП\r\nВідеокарта: NVIDIA GeForce 8600/9600GT, ATI/AMD Radeon HD2600/3600\r\nDirectX: версії 11\r\nМережа: широкосмугове підключення до Інтернету\r\nМісце на диску: 60 GB доступного місця\r\nЗвукова карта: сумісна з DirectX', 'ОС *: Windows 7 чи новішої версії\r\nПроцесор: двоядерний процесор Intel або AMD з частотою 2,8 ГГц\r\nОперативна пам’ять: 4 GB ОП\r\nВідеокарта: NVIDIA GeForce 8600/9600GT, ATI/AMD Radeon HD2600/3600\r\nDirectX: версії 11\r\nМережа: широкосмугове підключення до Інтернету\r\nМісце на диску: 60 GB доступного місця\r\nЗвукова карта: сумісна з DirectX', 'img/Game/Dota-2/sc1.jpg', 'img/Game/Dota-2/sc2.jpg', 'img/Game/Dota-2/sc3.jpg', 'img/Game/Dota-2/sc4.jpg', 'img/Game/Dota-2/sc5.jpg', 1),
(9, 'Left 4 Dead', 'Left 4 Dead від Valve, творців Counter-Strike і Half-Life, — це кооперативний бойовик-жахи для ПК та Xbox 360, де четверо гравців потрапляють до епічної боротьби за виживання проти навал зомбі й жахливих монстрів-мутантів.\r\n\r\nДія L4D відбувається одразу після зомбі-апокаліпсису, а кооперативний режим виживання змушує вас пробивати шлях крізь інфікованих у чотирьох унікальних «фільмах», які ведуть ваших уцілілих дахами полишеного людьми мегаполісу, через провінційні містечка-привиди та чорні мов смола ліси у вашому пошуку втечі зі спустошеного епіцентру катастрофи, сповненого інфікованих ворогів. Кожен «фільм» складається з п’яти великих мап і його можуть грати від одного до чотирьох гравців з особливим наголосом на командній стратегії та цілях.\r\n\r\nНова технологія штучного інтелекту (The AI Director) створена для генерування унікального ігроладного досвіду під час кожної нової гри. Штучний інтелект підлаштовує частоту й агресивність атак зомбі до вашої ситуації, кидаючи вас просто в центр у міру стрімкого голлівудського фільму жахів.\r\n\r\n- Затягувальний активний самітний, кооперативний та багатокористувацький ігролад від творців Counter-Strike і Half-Life.\r\n- Режим протистояння дозволить вам змагатися чотири на чотири з друзями, які грають за людей-утікачів або за зомбі-босів, що намагаються знищити вцілілих будь-якою ціною.\r\n- Перевірте, як довго ви з друзями зможете втриматися проти навали інфікованих у новому режимі виживання.\r\n- Просунутий штучний інтелект динамічно створює напружений та унікальний досвід для кожної гри.\r\n- 20 мап, 10 видів зброї й нескінченні можливості в чотирьох «фільмах».\r\n- Пошук матчів, статистика, рейтинги й система нагород, які мотивують спільну гру.\r\n- Коментарі ігрових дизайнерів дозволяють гравцям потрапити за лаштунки створення гри.\r\n- На основі Source і Steam.', NULL, NULL, 'img/Game/Left-4-Dead.jpg', 'Шутер, Зомбі, Кооператив, Хоррор', 'Valve', 'Valve', 24, 0, '2008-11-17', 'Supported OS: Windows® 7 32/64-bit / Vista 32/64 / XP\r\nProcessor: Pentium 4 3.0GHz\r\nMemory: 1 GB\r\nGraphics: 128 MB, Shader model 2.0, ATI 9600, NVidia 6600 or better\r\nHard Drive: At least 7.5 GB of free space\r\nSound Card: DirectX 9.0c compatible sound card', 'Supported OS: Windows® 7 32/64-bit / Vista 32/64 / XP\r\nProcessor: Intel core 2 duo 2.4GHz\r\nMemory: 1 GB\r\nGraphics: Shader model 3.0, NVidia 7600, ATI X1600 or better', 'img/Game/Left-4-Dead/sc1.jpg', 'img/Game/Left-4-Dead/sc2.jpg', 'img/Game/Left-4-Dead/sc3.jpg', 'img/Game/Left-4-Dead/sc4.jpg', 'img/Game/Left-4-Dead/sc5.jpg', 1),
(10, 'Left 4 Dead 2', 'Left 4 Dead 2 (L4D2) — це довгоочікуване зомбопокаліптичне продовження кооперативної стрілянки №1.\r\nУ цьому бойовику жахів від першої особи ви разом із друзями проходите п’ять великих кампаній містами, болотами та кладовищами — від Саванни до Нового Орлеану.\r\nВи гратимете за одного з чотирьох уцілілих, озброєного широким і руйнівним арсеналом класичної й поліпшеної зброї. В нагоді зігнати свою злість на інфікованих стане не лише вогнепальна зброя, а й різні рукопашні знаряддя для різанини — від бензопил і сокир до смертельних сковорідок.\r\nЦю зброю ви зможете випробувати на трьох жахливих і грізних нових особливих інфікованих (або зіграти за них у протистоянні). Окрім того, ви зустрінете п’ятьох нових звичайних інфікованих, таких як страшні болотяники.\r\nЩоби зробити динаміку L4D ще несамовитішою й заповнити ігролад дією, ми поліпшили штучний інтелект до версії 2.0. Цей поліпшений штучний інтелект тепер може процедурно змінювати погоду під час сутичок і ваш шлях, а також коригувати відповідно до вашого стилю гри кількість ворогів, ефекти й звуки. L4D2 обіцяє задовольнити найвибагливіших до випробувань гравців, підлаштовуючи кожну ігрову сесію під ваш стиль гри.', NULL, NULL, 'img/Game/Left-4-Dead-2.jpg', 'Шутер, Зомбі, Кооператив, Хоррор', 'Valve', 'Valve', 24, 0, '2009-11-17', 'ОС *: Windows® 7 32/64-bit / Vista 32/64 / XP\r\nПроцесор: Pentium 4 3.0GHz\r\nОперативна пам’ять: 2 GB ОП\r\nВідеокарта: Video card with 128 MB, Shader model 2.0. ATI X800, NVidia 6600 or better\r\nDirectX: версії 9.0c\r\nМісце на диску: 13 GB доступного місця\r\nЗвукова карта: DirectX 9.0c compatible sound card', 'ОС *: Windows® 7 32/64-bit / Vista 32/64 / XP\r\nПроцесор: Intel core 2 duo 2.4GHz\r\nОперативна пам’ять: 2 GB ОП\r\nВідеокарта: Video Card Shader model 3.0. NVidia 7600, ATI X1600 or better\r\nDirectX: версії 9.0c\r\nМісце на диску: 13 GB доступного місця\r\nЗвукова карта: DirectX 9.0c compatible sound card', 'img/Game/Left-4-Dead-2/sc1.jpg', 'img/Game/Left-4-Dead-2/sc2.jpg', 'img/Game/Left-4-Dead-2/sc3.jpg', 'img/Game/Left-4-Dead-2/sc4.jpg', 'img/Game/Left-4-Dead-2/sc5.jpg', 1),
(11, 'Minecraft', 'Досліджуйте випадково згенеровані світи та будуйте дивовижні речі: від найпростіших будиночків до найвеличніших замків. Грайте у творчому режимі з необмеженими ресурсами або повністю заглибтесь у світ у режимі виживання, крафтячи зброю та броню, щоб відбиватися від небезпечних мобів. Видирайтесь у скелясті гори, розкопуйте заплутані печери та видобувайте руду у багатих жилах. Досліджуйте біоми пишних печер і печер зі сталактитами й сталагмітами. Запаліть свічки та продемонструйте ваші навички в дослідженні печер і підкоренні гір!', NULL, NULL, 'img/Game/Minecraft.jpg', 'Пісочниця, Виживання, Відкритий світ, Пригоди', 'Mojang/Microsoft Studios', 'Microsoft Studios', 25, 0, '2015-07-14', 'ОС: Windows 7 или новее (64-бит)\r\nПроцессор: Intel Core i3-3210 3.2 ГГц / AMD A8-7600 APU 3.1 ГГц или эквивалент\r\nОперативная память: 4 GB ОП\r\nВидеокарта: Intel HD Graphics 4000 (Ivy Bridge) или AMD Radeon R5 (Kaveri) с поддержкой OpenGL 4.4\r\nМесто на диске: 1 GB доступного места (для установки игры, миры и сохранения требуют дополнительного места)\r\nСеть: Широкополосное подключение к Интернету (для загрузки игры и мультиплеера)\r\nЗвуковая карта: Любая совместимая звуковая карта', 'ОС: Windows 10 или 11 (64-бит)\r\nПроцессор: Intel Core i5-4690 3.5 ГГц / AMD A10-7800 APU 3.5 ГГц или новее\r\nОперативная память: 8 GB ОП\r\nВидеокарта: NVIDIA GeForce 700-й серии или AMD Radeon Rx 200-й серии (дискретная карта) с поддержкой OpenGL 4.5\r\nМесто на диске: 4 GB доступного места (рекомендуется SSD)\r\nСеть: Широкополосное подключение к Интернету\r\nЗвуковая карта: Любая совместимая звуковая карта', 'img/Game/Minecraft/sc1.jpg', 'img/Game/Minecraft/sc2.jpg', 'img/Game/Minecraft/sc3.jpg', 'img/Game/Minecraft/sc4.jpg', 'img/Game/Minecraft/sc5.jpg', 1),
(12, 'Hollow Knight', 'Hollow Knight — це класична 2D-гра в жанрі action-adventure, дія якої розгортається у величезному взаємопов\'язаному світі. Досліджуйте звивисті печери, стародавні міста і смертоносні пустки; бийтеся з оскверненими істотами і подружіться з дивними комахами; розгадайте стародавні таємниці в самому серці королівства.\r\n\r\nОсобливості гри:\r\n- Класична гра з боковим скролінгом, з усіма сучасними доповненнями.\r\n- Тісно налагоджене 2D-керування. Ухиляйтеся, біжіть і пробивайтеся навіть через найнебезпечніших супротивників.\r\n- Досліджуйте величезний взаємопов\'язаний світ забутих шосе, зарослих диких місцевостей і зруйнованих міст.\r\n- Прокладіть свій власний шлях! Світ Hallownest великий і відкритий. Вибирайте, якими шляхами йти, з якими ворогами битися, і знайдіть свій власний шлях вперед.\r\n- Розвивайтеся за допомогою нових потужних навичок і здібностей! Отримуйте заклинання, силу і швидкість. Піднімайтеся на нові висоти на ефірних крилах. - Мчіться вперед у спалаху вогню. Знищуйте ворогів вогняною Душею!\r\n- Одягайте амулети! Стародавні реліквії, що дарують дивні нові сили та здібності. Оберіть улюблені та зробіть свою подорож унікальною!\r\n- Величезна кількість милих і моторошних персонажів, оживлених за допомогою традиційної 2D-анімації кадр за кадром.\r\n- Понад 130 ворогів! 30 епічних босів! Зустріньтеся з лютими звірами та переможіть стародавніх лицарів у своїй подорожі королівством. Відстежте кожного викривленого ворога та додайте їх до свого щоденника мисливця!\r\n- Зануртеся в думки за допомогою Dream Nail. Відкрийте для себе зовсім іншу сторону персонажів, яких ви зустрічаєте, і ворогів, з якими стикаєтеся.\r\n- Прекрасні намальовані пейзажі з екстравагантним паралаксом надають унікального відчуття глибини світу, зображеному збоку.\r\n- Плануйте свою подорож за допомогою розширених інструментів картографування. - Купуйте компаси, пера, карти та шпильки, щоб краще зрозуміти численні вигадливі ландшафти Hollow Knight.\r\n- Захоплююча, інтимна музика, складена Крістофером Ларкіном, супроводжує гравця в його подорожі. Музика віддзеркалює велич і сум цивілізації, що прийшла до занепаду.\r\n- Пройдіть Hollow Knight, щоб розблокувати режим Steel Soul, найскладніший виклик!', 'Одиночна гра,Досягнення,Підтримка контролерів,Хмарні збереження', 'Українська,Англійська,Французька,Німецька,Іспанська', 'img/Game/Hollow-Knight.jpg', 'Метроїдванія, Платформер, Інді, Екшн', 'Team Cherry Games', 'Team Cherry Games', 27, 0, '2017-02-24', 'ОС *: Windows 7 (64bit)\r\nПроцесор: Intel Core 2 Duo E5200\r\nОперативна пам’ять: 4 GB ОП\r\nВідеокарта: GeForce 9800GTX+ (1GB)\r\nDirectX: версії 10\r\nМісце на диску: 9 GB доступного місця\r\nДодаткові примітки: 1080p, 16:9 recommended', 'ОС: Windows 10 (64bit)\r\nПроцесор: Intel Core i5\r\nОперативна пам’ять: 8 GB ОП\r\nВідеокарта: GeForce GTX 560\r\nDirectX: версії 11\r\nМісце на диску: 9 GB доступного місця\r\nДодаткові примітки: 1080p, 16:9 recommended', 'img/Game/Hollow-Knight/sc1.jpg', 'img/Game/Hollow-Knight/sc2.jpg', 'img/Game/Hollow-Knight/sc3.jpg', 'img/Game/Hollow-Knight/sc4.jpg', 'img/Game/Hollow-Knight/sc5.jpg', 1),
(13, 'Hollow Knight: Silksong', 'Станьте принцесою-лицаркою\r\nУ ролі смертоносної мисливиці Хорнет вирушайте в пригоду королівством, де панують шовк і пісні! Потрапивши в полон і опинившись у цьому незнайомому світі, готуйтеся до битв із могутніми ворогами та розгадування стародавніх таємниць, піднімаючись у смертельно небезпечному паломництві до вершини королівства.\r\n\r\nHollow Knight: Silksong — це епічне продовження нагородженої преміями пригодницької гри Hollow Knight. Подорожуйте до нових земель, відкривайте нові сили, боріться з величезними ордами комах і звірів та розкривайте таємниці, пов\'язані з вашою природою та минулим.\r\n\r\nОсобливості гри:\r\n- Відкрийте для себе занепале королівство комах Фарлум! Досліджуйте моховиті гроти, позолочені міста та затуманені болота, піднімаючись до сяючої цитаделі на вершині світу.\r\n- Беріть участь у смертельних акробатичних боях! Використовуйте величезний набір смертельних прийомів, танцюючи між ворогами у швидких, красивих боях.\r\n- Створюйте потужні інструменти! Опануйте постійно зростаючий арсенал зброї, пасток і механізмів, щоб перемогти ворогів і досліджувати нові висоти.\r\n- Виконуйте шокуючі завдання! Полюйте на рідкісних звірів і розгадуйте стародавні таємниці, щоб виконати бажання пригноблених і повернути надію королівству.\r\n- Зіткніться з понад 200 лютими ворогами! Звірі та мисливці, монстри та лицарі. Переможіть їх усіх завдяки своїй хоробрості та вправності!\r\n- Переможіть понад 40 легендарних босів! Бийтеся з легендарними героями та поваленими королями в епічних боях, щоб вирішити долю королівства.\r\n- Випробуйте режим «Сталева душа»! Після завоювання королівства випробуйте свої навички в новому режимі, який представляє більш грізний виклик.\r\n- Насолоджуйтесь приголомшливою оркестровою музикою! Нагородами відзначений композитор Hollow Knight, Крістофер Ларкін, привносить в пригоду меланхолійні мелодії, симфонічні струнні та захоплюючі, проникливі теми босів.', 'Одиночна гра,Досягнення,Підтримка контролерів,Хмарні збереження', 'Українська,Англійська,Французька,Німецька,Іспанська', 'img/Game/Hollow-Knight-Silksong.jpg', 'Метроїдванія, Платформер, Інді, Екшн', 'Team Cherry Games', 'Team Cherry Games', 27, 1, '2025-09-04', 'Потребує 64-бітних процесора та операційної системи\r\nОС: Windows 10 version 21H1 (build 19043) or newer\r\nПроцесор: Intel Core i3-3240, AMD FX-4300\r\nОперативна пам’ять: 4 GB ОП\r\nВідеокарта: GeForce GTX 560 Ti (1GB), Radeon HD 7750 (1GB)\r\nDirectX: версії 10\r\nМісце на диску: 8 GB доступного місця', 'Потребує 64-бітних процесора та операційної системи\r\nОС: Windows 10 version 21H1 (build 19043) or newer\r\nПроцесор: Intel Core i5-3470\r\nОперативна пам’ять: 8 GB ОП\r\nВідеокарта: GeForce GTX 1050 (2GB), Radeon R9 380 (2GB)\r\nDirectX: версії 10\r\nМісце на диску: 8 GB доступного місця', 'img/Game/Hollow-Knight-Silksong/sc1.jpg', 'img/Game/Hollow-Knight-Silksong/sc2.jpg', 'img/Game/Hollow-Knight-Silksong/sc3.jpg', 'img/Game/Hollow-Knight-Silksong/sc4.jpg', 'img/Game/Hollow-Knight-Silksong/sc5.jpg', 1),
(17, 'Відьмак 3: Дикий гін', 'Ви — Ґеральт із Рівії, найманий мисливець на чудовиськ. Перед вами цілий континент, просяклий війнами та заполонений різними потворами. Поточний контракт? Відшукайте Цірі — Дитя Пророцтва, живу зброю, що може докорінно змінити знаний світ.', 'Однокористувацька гра, Досягнення Steam, Колекційні картки Steam, Майстерня Steam, Зміна розміру тексту, Комфортність камери, Альтернативні кольори, Нестандартне регулювання гучності, Регульована складність, Без часових обмежень на введення, Збереження будь-коли, Стереозвук, Об’ємний звук, Часткова підтримка контролерів, Steam Cloud, Remote Play на планшеті, Remote Play на телевізорі, З підтримкою HDR, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), арабська, чеська, угорська, японська, корейська, польська, португальська (Бразилія), російська, китайська (традиційна), турецька, китайська (спрощена), іспанська (Латинська Америка)повністю озвучено цими мовами', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/ad9240e088f953a84aee814034c50a6a92bf4516/header.jpg?t=1768303991', 'Рольові ігри', 'CD PROJEKT RED', 'CD PROJEKT RED', 1, 0, '2026-03-11', 'ОС *: 64-bit Windows 7, 64-bit Windows 8 (8.1)\nПроцесор: Intel CPU Core i5-2500K 3.3GHz / AMD A10-5800K APU (3.8GHz)\nОперативна пам’ять: 6 GB ОП\nВідеокарта: Nvidia GPU GeForce GTX 660 / AMD GPU Radeon HD 7870\nDirectX: версії 11\nМісце на диску: 50 GB доступного місця', 'ОС: 64-bit Windows 10/11\nПроцесор: Intel Core i5-7400 / Ryzen 5 1600\nОперативна пам’ять: 8 GB ОП\nВідеокарта: Nvidia GTX 1070 / Radeon RX 480\nDirectX: версії 12\nМісце на диску: 50 GB доступного місця', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/ss_5710298af2318afd9aa72449ef29ac4a2ef64d8e.600x338.jpg?t=1768303991', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/ss_0901e64e9d4b8ebaea8348c194e7a3644d2d832d.600x338.jpg?t=1768303991', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/ss_112b1e176c1bd271d8a565eacb6feaf90f240bb2.600x338.jpg?t=1768303991', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/ss_d1b73b18cbcd5e9e412c7a1dead3c5cd7303d2ad.600x338.jpg?t=1768303991', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/ss_107600c1337accc09104f7a8aa7f275f23cad096.600x338.jpg?t=1768303991', 1),
(20, 'Minecraft Dungeons', 'Пробийте свій шлях в абсолютно новій пригодницькій грі, натхненій класичними данжен-кроулерами, дія якої розгортається у всесвіті Minecraft!', 'Однокористувацька гра, Багатокористувацька гра, Кооперативна гра, Мережева кооперативна гра, Кооперативна гра на спільному або розділеному екрані, Спільний/розділений екран, Міжплатформна багатокористувацька гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), японська, корейська, польська, португальська (Португалія), португальська (Бразилія), російська, китайська (спрощена), іспанська (Латинська Америка), шведська, китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1672970/header.jpg?t=1717003107', 'Бойовики, Пригоди, Рольові ігри', 'Mojang Studios, ‪Double Eleven', 'Xbox Game Studios', 25, 0, '2026-03-11', 'Потребує 64-бітних процесора та операційної системи\nОС *: Windows 10 (November 2019 Update or higher), 8 or 7 (64-bit with the latest updates; some functionality not supported on Windows 7 and 8)\nПроцесор: Core i5 2.8GHz or equivalent\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA GeForce GTX 660 or AMD Radeon HD 7870 or equivalent DX11 GPU\nDirectX: версії 11\nМісце на диску: 6 GB доступного місця\nДодаткові примітки: Performance increases with higher-end systems. Not supported on Windows 10S.', 'Потребує 64-бітних процесора та операційної системи\nОС *: Windows 10 (November 2019 Update or higher), 8 or 7 (64-bit with the latest updates; some functionality not supported on Windows 7 and 8)\nПроцесор: Core i5 2.8GHz or equivalent\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA GeForce GTX 660 or AMD Radeon HD 7870 or equivalent DX11 GPU\nDirectX: версії 11\nМісце на диску: 6 GB доступного місця\nДодаткові примітки: Performance increases with higher-end systems. Not supported on Windows 10S.', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1672970/ss_46ee31494b5d144d5ef6670cb5a1564abbc26fab.600x338.jpg?t=1717003107', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1672970/ss_73b488e696e3ae45f5d0a5750de524c231dab8a2.600x338.jpg?t=1717003107', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1672970/ss_9cb3efba6636610ec78eddd550147ed5ee7be3a0.600x338.jpg?t=1717003107', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1672970/ss_52883e4263c8f8bca14236118ab62c0f70f2c8d0.600x338.jpg?t=1717003107', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1672970/ss_65f1edd2c869ba7bb98da64f263ec1a8b591d645.600x338.jpg?t=1717003107', 1),
(21, 'Minecraft Legends', 'Відкрийте для себе таємниці Minecraft Legends, нової екшн-стратегічної гри. Дослідіть м\'яку землю багатих ресурсів і пишних біомів на межі руйнування. Прибули руйнівні піґліни, і саме ви маєте надихнути своїх союзників і вести їх у стратегічних боях за порятунок Верхнього світу!', 'Однокористувацька гра, Багатокористувацька гра, Гравець проти гравця, Гравець проти гравця в мережі, Кооперативна гра, Мережева кооперативна гра, Міжплатформна багатокористувацька гра, Повна підтримка контролерів, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), арабська, болгарська, чеська, данська, нідерландська, фінська, грецька, угорська, японська, корейська, норвезька, польська, португальська (Португалія), португальська (Бразилія), російська, китайська (спрощена), іспанська (Латинська Америка), шведська, китайська (традиційна), турецька, українська', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1928870/header.jpg?t=1746133966', 'Бойовики, Стратегії', 'Mojang Studios, Blackbird Interactive', 'Xbox Game Studios', 25, 0, '2026-03-11', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10 (May 2020 Update or higher) or Windows 11\nПроцесор: Core i5 2.8GHz or equivalent\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA GeForce GTX 780 or AMD Radeon 285 or Intel HD 520 or equivalent DX12 GPU\nDirectX: версії 12\nМісце на диску: 24 GB доступного місця\nДодаткові примітки: Performance increases with higher-end systems. Not supported on Windows 10S.', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10 (May 2020 Update or higher) or Windows 11\nПроцесор: Core i5 3.4GHz or equivalent\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA GTX 1060 or AMD Radeon 580\nDirectX: версії 12\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 24 GB доступного місця\nДодаткові примітки: Performance increases with higher-end systems. Not supported on Windows 10S.', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1928870/ss_65720eb73a2dd8fc993172cfbfcdc8fe40ec44c2.600x338.jpg?t=1746133966', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1928870/ss_87b5f26fd65475c2605013fae8f17c5085cbd024.600x338.jpg?t=1746133966', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1928870/ss_4dba4e59eabcf5c3631e5c28b52ffcae46d3bad8.600x338.jpg?t=1746133966', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1928870/ss_edc777c7f4d3e0ecfdf26d0cd975017a0f98cb8f.600x338.jpg?t=1746133966', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1928870/ss_07751f1c6d097298907a6be7df2cf21ad795acc4.600x338.jpg?t=1746133966', 1),
(23, 'Portal', 'Portal™ — нова гра від Valve для одного гравця. Portal, дії якого відбуваються в загадкових лабораторіях компанії Aperture, був названий однією з найбільш новаторських ігор сучасності і пропонує увазі гравця безліч годин несхожого ні на що ігроладу.', 'Однокористувацька гра, Досягнення Steam, Повна підтримка контролерів, З субтитрами, Комфортність камери, Нестандартне регулювання гучності, Збереження будь-коли, Стереозвук, Параметри субтитрів, Об’ємний звук, Містить редактор рівнів, Містить Source SDK, З коментарями від розробників, Remote Play на телефоні, Remote Play на планшеті, Сімейна бібліотека', 'англійська, французька, німецька, російська, данська, нідерландська, фінська, італійська, японська, норвезька, польська, португальська (Португалія), китайська (спрощена), іспанська (Іспанія), шведська, китайська (традиційна), корейська, болгарська, чеська, грецька, угорська, португальська (Бразилія), румунська, іспанська (Латинська Америка), тайська, турецька, українська', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/400/header.jpg?t=1745368554', 'Бойовики', 'Valve', 'Valve', NULL, 0, '2026-03-12', 'процесор із частотою 1,7 ГГц, 512 МБ оперативної пам’яті, графічна плата з підтримкою DirectX&reg; 8.1 (також необхідна підтримка SSE), Windows&reg; 7 (32/64-розрядна)/Vista/XP, миша, клавіатура, з’єднання з Інтернетом\nРекомендовані: процесор Pentium 4 (частота від 3 ГГц), 1 ГБ оперативної пам’яті, графічна плата з підтримкою DirectX&reg; 9, Windows&reg; 7 (32/64-розрядна)/Vista/XP, миша, клавіатура, з’єднання з Інтернетом', '', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/400/0000002582.600x338.jpg?t=1745368554', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/400/0000002583.600x338.jpg?t=1745368554', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/400/0000002584.600x338.jpg?t=1745368554', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/400/0000002585.600x338.jpg?t=1745368554', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/400/0000002586.600x338.jpg?t=1745368554', 1),
(24, 'Resident Evil', 'The game that defined the survival-horror genre is back! Check out the remastered HD version of Resident Evil.', 'Однокористувацька гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, Steam Cloud, Таблиці лідерів Steam, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), японська, китайська (спрощена), китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/304240/header.jpg?t=1768956401', 'Бойовики, Пригоди', 'CAPCOM Co., Ltd.', 'CAPCOM Co., Ltd.', 28, 1, '2026-03-12', 'ОС: Windows®10\nПроцесор: Intel® Core™ 2 Duo 2.4 GHz, AMD Athlon™ X2 2.8 GHz, or better\nОперативна пам’ять: 2 GB ОП\nВідеокарта: NVIDIA® GeForce® GTX260, ATI Radeon HD 6790, or better\nMonitor Resolution: 1024×768 or higher\nDirectX: версії 9.0c\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 20 GB доступного місця\nЗвукова карта: DirectSound compatible (must support DirectX 9.0c or higher)\nДодаткові примітки: Controller: Supports Keyboard +Mouse.  Genuine Xbox 360 PC compatible controller or comparable XInput-based gamepad recommended.Note: Some high end integrated graphics chips and modern gaming laptops with a discrete GPU may work but have not been tested, nor are they officially supported by Capcom.', 'ОС: Windows®10\nПроцесор: Intel® Core™ 2 Quad 2.7 GHz, AMD Phenom™ II X4 3.0 GHz or better\nОперативна пам’ять: 4 GB ОП\nВідеокарта: NVIDIA® GeForce® GTX 560, ATI Radeon HD 6950, or better\nMonitor Resolution: 1280×720 or higher\nDirectX: версії 9.0c\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 20 GB доступного місця\nЗвукова карта: DirectSound compatible (must support DirectX 9.0c or higher)\nДодаткові примітки: Controller: Supports Keyboard +Mouse. Genuine Xbox 360 PC compatible controller or comparable XInput-based gamepad recommended.Note: Some high end integrated graphics chips and modern gaming laptops with a discrete GPU may work but have not been tested, nor are they officially supported by Capcom.', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/304240/ss_d75906451d3a57bb8fc65fdea7ce09fbf1d3b726.600x338.jpg?t=1768956401', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/304240/ss_d27983bb8176325d81cf0ca83dfb30ec247e6f23.600x338.jpg?t=1768956401', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/304240/ss_ea73c8a2900d3954fac684104167a6a1eb778026.600x338.jpg?t=1768956401', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/304240/ss_2ad5847f8eb39c09187dfafc240bfd821d50a8ba.600x338.jpg?t=1768956401', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/304240/ss_8ff9a328bbfa668034356554ceb3d29a7dc7262f.600x338.jpg?t=1768956401', 1),
(25, 'Resident Evil Requiem', 'Реквієм за мертвими. Жах для живих. Готуйтеся до смертельної гонитви! На вас чекає напружена атмосфера, що пробере до кісток.', 'Однокористувацька гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, З субтитрами, Steam Cloud, З підтримкою HDR, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), арабська, іспанська (Латинська Америка), португальська (Бразилія), польська, російська, китайська (спрощена), китайська (традиційна), японська, корейська', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3764200/ce5437442768e38eb575f205ab9397d0264017b0/header.jpg?t=1772587704', 'Бойовики, Пригоди', 'CAPCOM Co., Ltd.', 'CAPCOM Co., Ltd.', 28, 1, '2026-03-12', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 11 (64bit required)\nПроцесор: Intel corei5-8500 / AMD Ryzen 5 3500\nОперативна пам’ять: 16 GB ОП\nВідеокарта: GeForce GTX 1660 6GB /  Radeon RX 5500 XT 8GB\nDirectX: версії 12\nДодаткові примітки: Supports 1080p gameplay (using upscaling, native resolution of 640p)/30fps. Note: Frame rate may drop when the processing load is high. An SSD is required.', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 11 (64bit required)\nПроцесор: Intel Core i7-8700 / AMD Ryzen 5 5500\nОперативна пам’ять: 16 GB ОП\nВідеокарта: GeForce RTX 2060 Super 8GB / Radeon RX 6600 8GB\nDirectX: версії 12\nДодаткові примітки: Supports 1080p gameplay (using upscaling, native resolution of 720p)/60fps. Note: Frame rate may drop when the processing load is high. An SSD is required.', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3764200/08af4e9398b8e45152bfbedce3bc24d22e2c0990/ss_08af4e9398b8e45152bfbedce3bc24d22e2c0990.600x338.jpg?t=1772587704', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3764200/4921eb5fb45f6b7a3b62195e47c6d7b4175935a8/ss_4921eb5fb45f6b7a3b62195e47c6d7b4175935a8.600x338.jpg?t=1772587704', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3764200/e7b791b703759aebe774b813eb29a364008552b8/ss_e7b791b703759aebe774b813eb29a364008552b8.600x338.jpg?t=1772587704', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3764200/bbe5c4e0ba4fc551d9389cf2411c2cefe413af1d/ss_bbe5c4e0ba4fc551d9389cf2411c2cefe413af1d.600x338.jpg?t=1772587704', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/3764200/9422ad111dfd5fe1df34352946c6aa715c23f0f6/ss_9422ad111dfd5fe1df34352946c6aa715c23f0f6.600x338.jpg?t=1772587704', 1),
(27, 'DARK SOULS™ III', 'Dark Souls continues to push the boundaries with the latest, ambitious chapter in the critically-acclaimed and genre-defining series. Prepare yourself and Embrace The Darkness!', 'Однокористувацька гра, Багатокористувацька гра, Кооперативна гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, Комфортність камери, Нестандартне регулювання гучності, Без часових обмежень на введення, Збереження будь-коли, Стереозвук, Об’ємний звук, Remote Play на телефоні, Remote Play на планшеті, Remote Play на телевізорі, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), польська, російська, португальська (Бразилія), китайська (спрощена), китайська (традиційна), японська, корейська', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/header.jpg?t=1748630784', 'Бойовики', 'FromSoftware, Inc.', 'FromSoftware, Inc., Bandai Namco Entertainment', 29, 0, '2026-03-23', 'ОС *: Windows 7 SP1 64bit, Windows 8.1 64bit Windows 10 64bit\nПроцесор: Intel Core i3-2100 / AMD® FX-6300\nОперативна пам’ять: 4 GB ОП\nВідеокарта: NVIDIA® GeForce GTX 750 Ti / ATI Radeon HD 7950\nDirectX: версії 11\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 25 GB доступного місця\nЗвукова карта: DirectX 11 sound device\nДодаткові примітки: Internet connection required for online play and product activation', 'ОС *: Windows 7 SP1 64bit, Windows 8.1 64bit Windows 10 64bit\nПроцесор: Intel Core i7-3770 / AMD® FX-8350\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA® GeForce GTX 970 / ATI Radeon R9 series\nDirectX: версії 11\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 25 GB доступного місця\nЗвукова карта: DirectX 11 sound device\nДодаткові примітки: Internet connection required for online play and product activation', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/ss_5efd318b85a3917d1c6e717f4cb813b47547cd6f.600x338.jpg?t=1748630784', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/ss_1c0fa39091901496d77cf4cecfea4ffb056d6452.600x338.jpg?t=1748630784', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/ss_1318a04ef11d87f38aebe6d47a96124f8f888ca8.600x338.jpg?t=1748630784', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/ss_61524dee9ebf72d462638f21adbbbea4c93d791d.600x338.jpg?t=1748630784', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/374320/ss_fe1dc6761a9004aa39c2e6e62181593b7263edf9.600x338.jpg?t=1748630784', 1),
(28, 'DARK SOULS™: REMASTERED', 'Then, there was fire. Re-experience the critically acclaimed, genre-defining game that started it all. Beautifully remastered, return to Lordran in stunning high-definition detail running at 60fps.', 'Однокористувацька гра, Багатокористувацька гра, Досягнення Steam, Повна підтримка контролерів, Remote Play на телефоні, Remote Play на планшеті, Remote Play на телевізорі, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), японська, корейська, польська, португальська (Бразилія), російська, китайська (спрощена), китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570940/header.jpg?t=1764975651', 'Бойовики', 'QLOC, FromSoftware, Inc.', 'FromSoftware, Inc., Bandai Namco Entertainment', 29, 0, '2026-03-23', 'Потребує 64-бітних процесора та операційної системи\nОС *: Windows 7 64-bit, Service Pack 1\nПроцесор: Intel Core i5-2300 2.8 GHz / AMD FX-6300, 3.5 GHz\nОперативна пам’ять: 6 GB ОП\nВідеокарта: GeForce GTX 460, 1 GB / Radeon HD 6870, 1 GB\nDirectX: версії 11\nМісце на диску: 8 GB доступного місця\nЗвукова карта: DirectX 11 sound device\nДодаткові примітки: Low Settings, 60 FPS @ 1080p', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10 64-bit\nПроцесор: Intel Core i5-4570 3.2 GHz / AMD FX-8350 4.2 GHz\nОперативна пам’ять: 8 GB ОП\nВідеокарта: GeForce GTX 660, 2 GB / Radeon HD 7870, 2 GB\nDirectX: версії 11\nМісце на диску: 8 GB доступного місця\nЗвукова карта: DirectX 11 sound device\nДодаткові примітки: High Settings, 60 FPS @ 1080p', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570940/ss_3a71463e4ccaf28c5c27f6cf8d32a3a125f45404.600x338.jpg?t=1764975651', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570940/ss_92b2ba470cbfdb8839b649b3f478e5531dd81a17.600x338.jpg?t=1764975651', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570940/ss_626cc310dc9ac7fb146011582c864a35e5f3e381.600x338.jpg?t=1764975651', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570940/ss_f1617a419eb3b0cd877ec71230c59aa2672b62dc.600x338.jpg?t=1764975651', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/570940/ss_f60f54e58b13d0744853672ccd35810397e3fa26.600x338.jpg?t=1764975651', 1),
(29, 'DARK SOULS™ II: Scholar of the First Sin', 'DARK SOULS™ II: Scholar of the First Sin brings the franchise’s renowned obscurity &amp; gripping gameplay to a new level. Join the dark journey and experience overwhelming enemy encounters, diabolical hazards, and unrelenting challenge.', 'Однокористувацька гра, Багатокористувацька гра, Кооперативна гра, Досягнення Steam, Повна підтримка контролерів, Remote Play на телефоні, Remote Play на планшеті, Remote Play на телевізорі, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), польська, португальська (Бразилія), російська, корейська, китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/335300/header.jpg?t=1760721873', 'Бойовики, Рольові ігри', 'FromSoftware, Inc.', 'Bandai Namco Entertainment, FromSoftware, Inc.', 29, 0, '2026-03-23', 'ОС *: Windows 7 SP1 64bit, Windows 8.1 64bit\nПроцесор: AMD® A8 3870 3,6 Ghz or Intel® Core ™ i3 2100 3.1Ghz\nОперативна пам’ять: 4 GB ОП\nВідеокарта: NVIDIA® GeForce  GTX 465 / ATI Radeon TM HD 6870\nDirectX: версії 11\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 23 GB доступного місця\nЗвукова карта: DirectX 11 sound device', 'ОС *: Windows 7 SP1 64bit, Windows 8.1 64bit\nПроцесор: AMD® FX 8150 3.6 GHz or Intel® Core™ i7 2600 3.4 GHz\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA® GeForce® GTX 750, ATI Radeon™ HD 7850\nDirectX: версії 11\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 23 GB доступного місця\nЗвукова карта: DirectX 11 sound device', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/335300/ss_589f08dc4d8580785923c89749101a15014bcdf9.600x338.jpg?t=1760721873', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/335300/ss_724c043f3005c09c71e683e4d4b633711d7ccd49.600x338.jpg?t=1760721873', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/335300/ss_a6af51265aac7894c2f233f67089715899d9fe7c.600x338.jpg?t=1760721873', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/335300/ss_b9e5f5afb7358b242d4a669cf6f622de55edf1a0.600x338.jpg?t=1760721873', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/335300/ss_fe75766ecb5828879ce1ee74b0ff345343ec33b2.600x338.jpg?t=1760721873', 1);
INSERT INTO `games` (`id`, `title`, `description`, `features`, `languages`, `cover_url`, `tags`, `developer`, `publisher`, `publisher_id`, `is_in_hero_slider`, `release_date`, `sys_min`, `sys_rec`, `screenshot1`, `screenshot2`, `screenshot3`, `screenshot4`, `screenshot5`, `is_approved`) VALUES
(30, 'DARK SOULS™ II', 'Developed by FROM SOFTWARE, DARK SOULS™ II is the highly anticipated sequel to the gruelling 2011 breakout hit Dark Souls. The unique old-school action RPG experience captivated imaginations of gamers worldwide with incredible challenge and intense emotional reward.', 'Однокористувацька гра, Багатокористувацька гра, Кооперативна гра, Досягнення Steam, Повна підтримка контролерів, Remote Play на телефоні, Remote Play на планшеті, Remote Play на телевізорі, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), польська, російська, корейська, португальська (Бразилія), китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/236430/header.jpg?t=1726158600', 'Бойовики, Рольові ігри', 'FromSoftware, Inc.', 'BANDAI NAMCO Entertainment, FromSoftware, Inc.', 29, 0, '2026-03-23', 'ОС *: Windows Vista SP2, Windows 7 SP1, Windows 8\nПроцесор: AMD® Phenom II™ X2 555 3.2Ghz or Intel® Pentium Core ™ 2 Duo E8500 3.17Ghz\nОперативна пам’ять: 2 GB ОП\nВідеокарта: NVIDIA® GeForce® 9600GT, ATI Radeon™ HD 5870\nDirectX: версії 9.0c\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 12 GB доступного місця\nЗвукова карта: DirectX 9 sound device\nДодаткові примітки: Controller support: Microsoft Xbox 360® Controller for Windows® (or equivalent) recommended', 'ОС *: Windows 7 SP1, Windows 8\nПроцесор: Intel® CoreTM i3 2100 3.10GHz or AMD® A8 3870K 3.0GHz\nОперативна пам’ять: 4 GB ОП\nВідеокарта: NVIDIA® GeForce® GTX 750 or ATI Radeon™ HD 6870 or higher\nDirectX: версії 9.0c\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 15 GB доступного місця\nЗвукова карта: DirectX 9 sound device\nДодаткові примітки: Controller support: Microsoft Xbox 360® Controller for Windows® (or equivalent) recommended', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/236430/ss_811a32f3abdf906cce50e204e849880e71583740.600x338.jpg?t=1726158600', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/236430/ss_abbbb6c1a1878f591e6a895a17a1870c62380161.600x338.jpg?t=1726158600', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/236430/ss_821ac8a59caa024e75c49087947efbd71a82b237.600x338.jpg?t=1726158600', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/236430/ss_0429fae1aa460aa56061190bcaa0592169fca2a4.600x338.jpg?t=1726158600', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/236430/ss_87fc714a33c2a1b34e64152679685b3e0fc1c3cb.600x338.jpg?t=1726158600', 1),
(31, 'Sekiro™: Shadows Die Twice - GOTY Edition', 'Game of the Year - The Game Awards 2019 Best Action Game of 2019 - IGN Carve your own clever path to vengeance in the award winning adventure from developer FromSoftware, creators of Bloodborne and the Dark Souls series. Take Revenge. Restore Your Honor. Kill Ingeniously.', 'Однокористувацька гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, Steam Cloud, Remote Play на планшеті, Remote Play на телевізорі, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), японська, корейська, польська, російська, китайська (спрощена), тайська, китайська (традиційна), португальська (Бразилія)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/header.jpg?t=1762888662', 'Бойовики, Пригоди', 'FromSoftware, Inc.', 'Activision (Excluding Japan and Asia), FromSoftware, Inc. (Japan), 方块游戏 (Asia)', 29, 0, '2026-03-23', 'ОС *: Windows 7 64-bit | Windows 8 64-bit | Windows 10 64-bit\nПроцесор: Intel Core i3-2100 | AMD FX-6300\nОперативна пам’ять: 4 GB ОП\nВідеокарта: NVIDIA GeForce GTX 760 | AMD Radeon HD 7950\nDirectX: версії 11\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 25 GB доступного місця\nЗвукова карта: DirectX 11 Compatible', 'ОС *: Windows 7 64-bit | Windows 8 64-bit | Windows 10 64-bit\nПроцесор: Intel Core i5-2500K | AMD Ryzen 5 1400\nОперативна пам’ять: 8 GB ОП\nВідеокарта: NVIDIA GeForce GTX 970 | AMD Radeon RX 570\nDirectX: версії 11\nМережа: широкосмугове підключення до Інтернету\nМісце на диску: 25 GB доступного місця\nЗвукова карта: DirectX 11 Compatible', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/ss_0f7b0f8ed9ffc49aba26f9328caa9a1d59ad60f0.600x338.jpg?t=1762888662', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/ss_2685dd844a2a523b6c7ec207d46a538db6a908cd.600x338.jpg?t=1762888662', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/ss_15f0e9982621aed44900215ad283811af0779b1d.600x338.jpg?t=1762888662', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/ss_1e6f5540866a5564d65df915c22fe1e57e336a6f.600x338.jpg?t=1762888662', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/ss_3d6b38c382c0eafb02dc90d22f33fd292e4e5cf3.600x338.jpg?t=1762888662', 1),
(32, 'Metal Wolf Chaos XD', 'Metal Wolf Chaos XD is a modernized re-release of FromSoftware\'s 2004 mech shooter with upgraded visual fidelity, refined controls and gameplay, a new save system, and 4K + 16:9 support for modern displays.', 'Однокористувацька гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, Steam Cloud, Таблиці лідерів Steam, Remote Play на телевізорі, Сімейна бібліотека', 'англійська, японська, французька, німецька, іспанська (Іспанія), корейська, португальська (Бразилія), російська, китайська (спрощена), китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/820630/header.jpg?t=1659054912', 'Бойовики', 'General Arcade, FromSoftware, Inc', 'Devolver Digital', 29, 0, '2026-03-23', 'ОС *: Windows 7 SP1 32/64bit Windows 8.1 32/64bit Windows 10 32/64bit\nПроцесор: Intel Core i3-2100 3.1GHz / AMD Athlon II X4 645 AM3 3100GHz\nОперативна пам’ять: 4 GB ОП\nВідеокарта: NVIDIA® GeForce® GeForce GTX 550 Ti, 1GB VRAM ATI Radeon™ HD 6850, 1GB VRAM\nDirectX: версії 11\nМісце на диску: 10 GB доступного місця\nЗвукова карта: DirectX-compatible sound card or onboard audio chip\nДодаткові примітки: Controller Recommended', 'ОС: Windows 10 64bit\nПроцесор: Intel Core i5-4670K 3.4GHz / AMD FX-6350 3.9GHz\nОперативна пам’ять: 6 GB ОП\nВідеокарта: NVIDIA® GeForce® GeForce GTX 750, 2GB VRAM ATI Radeon™ R7 260X, 2GB VRAM\nDirectX: версії 11\nМісце на диску: 10 GB доступного місця\nЗвукова карта: DirectX-compatible sound card or onboard audio chip\nДодаткові примітки: Controller Recommended', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/820630/ss_41dafaec5ccb948240f54c7029a2794e209dd36a.600x338.jpg?t=1659054912', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/820630/ss_f497e2a3a01636bd1fcd8ab471566ac065dc3b04.600x338.jpg?t=1659054912', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/820630/ss_80e89acdd9eb324c65b8e384444d49479acf26a9.600x338.jpg?t=1659054912', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/820630/ss_44b1864174e0bb4e58d9d052034baaed1d426957.600x338.jpg?t=1659054912', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/820630/ss_ec6f75635ba48f7fe987750dca0ae2838059f1c9.600x338.jpg?t=1659054912', 1),
(33, 'ELDEN RING', 'THE CRITICALLY ACCLAIMED FANTASY ACTION RPG. Rise, Tarnished, and be guided by grace to brandish the power of the Elden Ring and become an Elden Lord in the Lands Between.', 'Однокористувацька гра, Багатокористувацька гра, Гравець проти гравця, Гравець проти гравця в мережі, Кооперативна гра, Мережева кооперативна гра, Досягнення Steam, Повна підтримка контролерів, Колекційні картки Steam, Комфортність камери, Нестандартне регулювання гучності, Без часових обмежень на введення, Збереження будь-коли, Стереозвук, Об’ємний звук, Steam Cloud, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), японська, корейська, польська, португальська (Бразилія), російська, китайська (спрощена), іспанська (Латинська Америка), тайська, китайська (традиційна), арабська', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/header.jpg?t=1767883716', 'Бойовики, Рольові ігри', 'FromSoftware, Inc.', 'FromSoftware, Inc., Bandai Namco Entertainment', 29, 0, '2026-03-23', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10\nПроцесор: INTEL CORE I5-8400 or AMD RYZEN 3 3300X\nОперативна пам’ять: 12 GB ОП\nВідеокарта: NVIDIA GEFORCE GTX 1060 3 GB or AMD RADEON RX 580 4 GB\nDirectX: версії 12\nМісце на диску: 60 GB доступного місця\nЗвукова карта: Windows Compatible Audio Device\nДодаткові примітки: ', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10/11\nПроцесор: INTEL CORE I7-8700K or AMD RYZEN 5 3600X\nОперативна пам’ять: 16 GB ОП\nВідеокарта: NVIDIA GEFORCE GTX 1070 8 GB or AMD RADEON RX VEGA 56 8 GB\nDirectX: версії 12\nМісце на диску: 60 GB доступного місця\nЗвукова карта: Windows Compatible Audio Device\nДодаткові примітки: ', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/ss_943bf6fe62352757d9070c1d33e50b92fe8539f1.600x338.jpg?t=1767883716', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/ss_dcdac9e4b26ac0ee5248bfd2967d764fd00cdb42.600x338.jpg?t=1767883716', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/ss_3c41384a24d86dddd58a8f61db77f9dc0bfda8b5.600x338.jpg?t=1767883716', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/ss_e0316c76f8197405c1312d072b84331dd735d60b.600x338.jpg?t=1767883716', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/ss_ef61b771ee6b269b1f0cb484233e07a0bfb5f81b.600x338.jpg?t=1767883716', 1),
(34, 'ELDEN RING NIGHTREIGN', 'ELDEN RING NIGHTREIGN — це окрема пригода у всесвіті ELDEN RING, яка пропонує новий ігровий досвід завдяки переосмисленню базового дизайну гри.', 'Однокористувацька гра, Багатокористувацька гра, Кооперативна гра, Мережева кооперативна гра, Досягнення Steam, Повна підтримка контролерів, Комфортність камери, Нестандартне регулювання гучності, Без часових обмежень на введення, Стереозвук, Об’ємний звук, Steam Cloud, Сімейна бібліотека', 'англійська, японська, французька, італійська, німецька, іспанська (Іспанія), арабська, корейська, польська, португальська (Бразилія), російська, китайська (спрощена), іспанська (Латинська Америка), тайська, китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2622380/header.jpg?t=1773099036', 'Бойовики, Рольові ігри', 'FromSoftware, Inc.', 'FromSoftware, Inc., Bandai Namco Entertainment', 29, 0, '2026-03-23', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10\nПроцесор: Intel Core i5-10600 / AMD RYZEN 5 5500\nОперативна пам’ять: 12 GB ОП\nВідеокарта: NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 580 4GB\nDirectX: версії 12\nМісце на диску: 30 GB доступного місця\nЗвукова карта: Windows compatible audio device', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 11\nПроцесор: Intel Core i5-11500 / AMD RYZEN 5 5600\nОперативна пам’ять: 16 GB ОП\nВідеокарта: NVIDIA GeForce GTX 1070 8GB / AMD Radeon RX Vega-56 8GB\nDirectX: версії 12\nМісце на диску: 30 GB доступного місця\nЗвукова карта: Windows compatible audio device', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2622380/ss_0b9594934db8a1457c915e200f9d0d9b447a3df4.600x338.jpg?t=1773099036', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2622380/ss_1deefb0b7ea597f4227777239910b4990aa0cc77.600x338.jpg?t=1773099036', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2622380/ss_802cd66236d951fba204fb9980e2c0c9213a264c.600x338.jpg?t=1773099036', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2622380/ss_b3ed8ab522f5965e46bc7c090cad9d018f937ae2.600x338.jpg?t=1773099036', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2622380/ss_edfd360b92d6f9b983b759fd837e664b86cd9563.600x338.jpg?t=1773099036', 1),
(35, 'ARMORED CORE™ VI FIRES OF RUBICON™', 'Новий бойовик, у якому основна концепція серії ARMORED CORE отримала сучасний розвиток за рахунок досвіду, накопиченого компанією FromSoftware за час розробки ігор цього жанру.', 'Однокористувацька гра, Багатокористувацька гра, Гравець проти гравця, Гравець проти гравця в мережі, Досягнення Steam, Колекційні картки Steam, Комфортність камери, Нестандартне регулювання гучності, Без часових обмежень на введення, Стереозвук, Об’ємний звук, Часткова підтримка контролерів, Steam Cloud, Сімейна бібліотека', 'англійська, французька, італійська, німецька, іспанська (Іспанія), японська, корейська, польська, португальська (Бразилія), російська, китайська (спрощена), іспанська (Латинська Америка), китайська (традиційна)', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888160/header.jpg?t=1748630736', 'Бойовики', 'FromSoftware, Inc.', 'FromSoftware, Inc., Bandai Namco Entertainment Inc.', 29, 0, '2026-03-23', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10\nПроцесор: Intel Core i7-4790K | Intel Core i5-8400 or AMD Ryzen 7 1800X | AMD Ryzen 5 2600\nОперативна пам’ять: 12 GB ОП\nВідеокарта: NVIDIA GeForce GTX 1650, 4 GB or AMD Radeon RX 480, 4 GB\nDirectX: версії 12\nМісце на диску: 60 GB доступного місця\nЗвукова карта: Windows Compatible Audio Device', 'Потребує 64-бітних процесора та операційної системи\nОС: Windows 10/11\nПроцесор: Intel Core i7-7700 | Intel Core i5-10400 or AMD Ryzen 7 2700X | AMD Ryzen 5 3600\nОперативна пам’ять: 12 GB ОП\nВідеокарта: NVIDIA GeForce GTX 1060, 6GB or AMD Radeon RX 590, 8GB or Intel Arc A750, 8GB\nDirectX: версії 12\nМісце на диску: 60 GB доступного місця\nЗвукова карта: Windows Compatible Audio Device', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888160/ss_549f55589c10866bc31243d277324e31ad155b29.600x338.jpg?t=1748630736', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888160/ss_dcd98899647b45246cfb296aa5a3b40b2ae87e9e.600x338.jpg?t=1748630736', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888160/ss_c92110c39a9c64376af5e8da31a0e6ffa9747334.600x338.jpg?t=1748630736', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888160/ss_6648f7e39998ae173b2271c6a325d4295e6db785.600x338.jpg?t=1748630736', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1888160/ss_f441df5b6d02d0cbc2635f29ec502d93058e025c.600x338.jpg?t=1748630736', 1),
(38, 'Bloodborne', 'Bloodborne — це рольова гра в стилі бойовик і ще один член серії ігор, схожих на душі. У грі немає ні попередників, ні наступників, що робить її єдиною частиною з одним доповненням: «Старі мисливці». Проте всесвіт Bloodborne також включає комікси та настільну гру.\r\nЗагалом нагадуючи серію Dark Souls, Bloodborne має дещо іншу механіку, а темп бою збільшений завдяки системі бою, яка виграє ризик. Іншою відмінністю є багатокористувацька гра, яка буває двох видів: ви можете викликати інших гравців у свою гру та битися з босами пліч-о-пліч або ви можете битися з самозванцями, які є іншими гравцями, які вторгаються у вашу гру.\r\nПротягом усієї гри ви змушені досліджувати величезний відкритий світ Yharnam, який виглядає як альтернативний всесвіт Англії вікторіанської доби, натхненний Лавкрафтом, як мисливець. Ви зустрічаєте жахливих босів, досліджуєте величезні території, будинки та відкриті простори, постійно ухиляючись, атакуючи та намагаючись не бути вбитими величезною кількістю ворожих NPC.', 'Одиночна гра,Кооператив,Досягнення,Підтримка контролерів,Хмарні збереження', 'Англійська,Французька,Німецька,Іспанська', 'https://media.rawg.io/media/games/214/214b29aeff13a0ae6a70fc4426e85991.jpg', 'Екшн, Рольові ігри', 'FromSoftware', 'Sony Computer Entertainment, Sony Interactive Entertainment', 29, 1, '2015-03-24', 'Ексклюзив PlayStation 4. Офіційний реліз на ПК відсутній.', 'Консоль PlayStation 4 або PlayStation 5.', 'https://media.rawg.io/media/screenshots/75a/75a67f69575ebfc412a70cdde7fb8923.jpg', 'https://media.rawg.io/media/screenshots/280/280b0e8492a247b718a3c14c41052a16.jpg', 'https://media.rawg.io/media/screenshots/d8e/d8e17e4899561a0a25e0728541b1cac9.jpg', 'https://media.rawg.io/media/screenshots/48c/48cfa5b44c1a6787971889bc7646ca47.jpg', '', 1),
(39, 'Ostriv', 'Острів – це містобудівна гра, що пропонує вам очолити уявне українське місто в 18-му столітті й продемонструвати свої творчі здібності та майстерність керівника', 'Однокористувацька гра, Steam Cloud, Сімейна бібліотека', 'англійська, українська', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/773790/header_ukrainian.jpg?t=1731437983', 'Інді, Симулятори, Стратегії, Дочасний доступ', 'yevhen8', 'yevhen8', 30, 0, '2026-03-24', 'ОС *: Windows 7, 8, 10, 11\nПроцесор: Quad-core\nОперативна пам’ять: 2 GB ОП\nВідеокарта: Будь-яка з підтримкою OpenGL 4.3\nМісце на диску: 600 MB доступного місця\nДодаткові примітки: Більші міста потребують більше системних ресурсів', 'ОС *: Windows 7, 8, 10, 11\nОперативна пам’ять: 8 GB ОП\nВідеокарта: GTX 770 або краща\nДодаткові примітки: Рекомендується роздільність екрана 1920 x 1080 або вище', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/773790/ss_5bfc6f9f247993686de36ebe504b232b4efb4ca8.600x338.jpg?t=1731437983', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/773790/ss_0be23e35d45f5ca7c68af06f438bcc47a5dbb46b.600x338.jpg?t=1731437983', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/773790/ss_07d0aa34f5ee0d6934e47af4466684ece091e9af.600x338.jpg?t=1731437983', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/773790/ss_378616ef132347559a3afc2a9afcf140ee285106.600x338.jpg?t=1731437983', 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/773790/ss_cbf85858487bd5663eccaca76c584c1f72085a84.600x338.jpg?t=1731437983', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `game_reviews`
--

CREATE TABLE `game_reviews` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `is_recommended` tinyint(1) NOT NULL,
  `comment_text` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `game_reviews`
--

INSERT INTO `game_reviews` (`id`, `game_id`, `user_id`, `is_recommended`, `comment_text`, `created_at`) VALUES
(1, 5, 2, 1, 'Прикольний проект який я пробував аж в 2025.\r\nВ цілому ідея з камерою і такими скрімер/жахастиками реалізована вдало, ще порадувало що тривалість гри коротка і це перекриває також інші мінуси які б вилізли з затягуванням гри.\r\nМені сподобалося і було зовсім не страшно.\r\n6,5/10.', '2025-11-14 08:40:20'),
(2, 12, 15, 1, 'Зацікавилася лише після того як побачила 2 частину, але не шкодую. Гра дуже затягує, має купу секретів, цікавих мешканців, і складних випробувань. Не зважаючи на це, хочеться грати безперервно)', '2025-12-03 13:13:23'),
(3, 17, 15, 1, 'Це просто шедевр, а не гра! Пройшов її вже тричі і кожного разу знаходжу щось нове. Відкритий світ неймовірний, квести прописані так, що навіть другорядні історії чіпляють за душу і змушують задуматися. Боївка, атмосфера, саундтрек — усе на найвищому рівні. Ну і звичайно, партія в гвінт посеред порятунку світу — це святе. 10/10, найкраща РПГ усіх часів!', '2026-03-23 17:51:44'),
(4, 5, 15, 1, 'Давно я так не лякався! Атмосфера в цій психлікарні просто неймовірно тисне на мізки. Те, що ти не можеш дати здачі мутантам, а маєш тільки тікати або ховатися з камерою, яка постійно жере батарейки — це геніально. Отримав просто величезну дозу адреналіну. Проходити обов\'язково вночі і в навушниках. Всім фанатам хоррорів — категорично рекомендую.', '2026-03-23 17:52:16'),
(5, 25, 15, 1, 'Серія Resident Evil як завжди на висоті. Графіка виглядає дуже реалістично і моторошно, а дизайн локацій і монстрів змушує попітніти. Дуже сподобався баланс між екшеном та класичним виживанням — патронів і аптечок постійно не вистачає, доводиться думати головою і цілитися точно. Пройшов на одному диханні, дуже крутий досвід і гідне продовження франшизи!', '2026-03-23 17:52:39'),
(6, 11, 15, 1, 'Це класика, в яку можна грати вічно. Зайшов у гру просто побудувати невеличку хатинку на вечір, а отямився через 5 годин, коли зводив величезний замок із автоматичними фермами і механізмами з редстоуну. Ідеальна гра, щоб розслабитися після важкого дня. Свобода дій і творчості тут просто безмежна. Однозначно лайк!', '2026-03-23 17:53:09');

-- --------------------------------------------------------

--
-- Структура таблицы `news_articles`
--

CREATE TABLE `news_articles` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `author_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `news_articles`
--

INSERT INTO `news_articles` (`id`, `title`, `content`, `image_url`, `author_id`, `created_at`) VALUES
(1, 'Ласкаво просимо до GameLib 1.0!', 'Ми раді нарешті запустити першу версію нашого сайту! GameLib створений як ідеальна платформа для каталогізації ваших особистих ігрових бібліотек.\r\n\r\nНаразі ми запустили основний функціонал:\r\n* Реєстрація користувачів та розробників.\r\n* Динамічний каталог ігор.\r\n* Можливість додавати ігри до \"Моєї бібліотеки\".\r\n* Система відгуків для ігор та \"стіна\" для розробників.\r\n\r\nПопереду ще багато оновлень, включаючи розширене редагування профілю та систему відстеження ігрового прогресу. Залишайтеся на зв\'язку!', 'img/news/news_1763318879.png', 1, '2025-11-16 18:47:59'),
(2, 'Ласкаво просимо до GameLib 1.0!', 'Ми раді нарешті запустити другу версію нашого сайту! GameLib створений як ідеальна платформа для каталогізації ваших особистих ігрових бібліотек.', 'img/news/news_1773308540.png', 1, '2026-03-12 09:42:20'),
(3, 'Оновлення GameLib: Система Бонусного Досвіду та перша Легенда Залу Слави!', 'Оновлення GameLib: Бонусний Досвід та перша Легенда!\r\n\r\nМи запускаємо систему Bonus XP, щоб нагороджувати найактивніших гравців нашої платформи!\r\n\r\nІ сьогодні ми робимо особливий подарунок. Абсолютний лідер нашого рейтингу, користувач IlliaB, отримує грандіозні 100 000 XP за свою шалену активність! Вітаємо в статусі справжньої Легенди Залу Слави із сяючою золотою рамкою профілю!\r\n\r\nХочете теж отримувати унікальні бонуси? Будьте активними: додавайте ігри в бібліотеку, пишіть круті відгуки та знаходьте друзів. Наступна нагорода від адміністрації може стати вашою!\r\n\r\nЗ любов\'ю, команда розробників GameLib.', 'img/news/news_1774289438.png', 1, '2026-03-23 18:10:38');

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 16, 'IvanK надіслав(ла) вам заявку в друзі.', 'profile.php', 1, '2026-03-23 14:35:59'),
(2, 14, 'PetroL прийняв(ла) вашу заявку в друзі.', 'profile.php?id=16', 0, '2026-03-23 14:36:39'),
(3, 15, 'PetroL надіслав(ла) вам заявку в друзі.', 'profile.php', 1, '2026-03-23 15:21:30'),
(4, 16, 'MariaS прийняв(ла) вашу заявку в друзі.', 'profile.php?id=15', 0, '2026-03-23 15:21:52'),
(5, 25, 'IlliaB тепер стежить за вашими оновленнями.', 'profile.php?id=15', 0, '2026-03-23 17:53:18'),
(6, 24, 'IlliaB тепер стежить за вашими оновленнями.', 'profile.php?id=15', 0, '2026-03-23 17:53:22'),
(7, 3, 'IlliaB тепер стежить за вашими оновленнями.', 'profile.php?id=15', 0, '2026-03-23 17:53:26'),
(8, 15, 'GameLib надіслав(ла) вам заявку в друзі.', 'profile.php', 1, '2026-03-23 18:35:02'),
(9, 29, 'IlliaB тепер стежить за вашими оновленнями.', 'profile.php?id=15', 1, '2026-03-23 21:39:36');

-- --------------------------------------------------------

--
-- Структура таблицы `review_likes`
--

CREATE TABLE `review_likes` (
  `id` int NOT NULL,
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `is_helpful` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `review_likes`
--

INSERT INTO `review_likes` (`id`, `review_id`, `user_id`, `is_helpful`, `created_at`) VALUES
(1, 2, 14, 1, '2026-03-23 14:20:12'),
(2, 1, 15, 1, '2026-03-23 17:52:13');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'img/avatars/default.png',
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bio` text COLLATE utf8mb4_general_ci,
  `birth_date` date DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `favorite_genre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `banner_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `accent_color` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '#00ff64',
  `showcase_game_id` int DEFAULT NULL,
  `privacy_profile` enum('public','friends','private') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'public',
  `steam_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bonus_xp` int NOT NULL DEFAULT '0',
  `show_level_frame` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `google_id`, `avatar_url`, `password_hash`, `user_role`, `created_at`, `bio`, `birth_date`, `country`, `favorite_genre`, `banner_url`, `accent_color`, `showcase_game_id`, `privacy_profile`, `steam_id`, `bonus_xp`, `show_level_frame`) VALUES
(1, 'GameLib', 'gamelib@gmail.com', NULL, 'img/avatars/user_1_1763065594.png', '$2y$10$FsLlspMT9whJ3EcUhd9n7eCVdhjWXQ74YTU5WORB4vpR3Rqeuntza', 'admin', '2025-11-11 23:15:37', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 10000000, 1),
(2, 'Dave', '0_user@gmail.com', NULL, 'img/avatars/user_2_1763112757.png', '$2y$10$wkCMEnhKaIJjqqEnLDk/luBcuJ7Ec/9k/nshMaVWagt8pb73uZAWe', 'user', '2025-11-12 09:02:30', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(3, 'Red Barrels', '0_developer@gmail.com', NULL, 'img/avatars/user_3_1762941200.jpg', '$2y$10$aMUmNOeSHeOMWL.iAosx5.1RUdo1uvGVnxfZq1sJwV.XQapniTpBq', 'developer', '2025-11-12 09:13:25', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(14, 'IvanK', '1_user@gmail.com', NULL, 'img/avatars/user_14_1764677642.png', '$2y$10$2S8DpZlTU.buqFMz4mmCye8mkz8WGYD/ZbUMKFRAl2ghVp7QWVfRi', 'user', '2025-11-16 20:21:28', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(15, 'IlliaB', '2_user@gmail.com', NULL, 'img/avatars/user_15_1774280687.jpg', '$2y$10$uuN3vn3/jfb0t/5/Wkp4AeodDsOPqB3gSrhaoN35rt.47zSzj6Ef6', 'user', '2025-11-16 20:22:19', '', '2006-09-10', 'Україна', '', 'img/banners/bg_15_1774279424.jpg', '#de35dc', 12, 'public', '76561198418915793', 500000, 1),
(16, 'PetroL', '3_user@gmail.com', NULL, 'img/avatars/user_16_1764677840.avif', '$2y$10$0uZqBkn4Ex1GcZ2YEkQaceN25D38L3xUvK0R.bvJKfFs.Cn4PcoBK', 'user', '2025-11-16 20:22:40', '', NULL, '', NULL, 'img/banners/bg_16_1774279277.jpg', '#00d2ed', 13, 'public', NULL, 0, 1),
(17, 'OlenaB', '4_user@gmail.com', NULL, 'img/avatars/user_17_1764677997.jpg', '$2y$10$IPjChRmvsqXw7b2SI8bk9eJ2mysJ0hwGMIBkOzjOL897TGt385Tlq', 'user', '2025-11-16 20:23:00', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(18, 'DmytroF', '5_user@gmail.com', NULL, 'img/avatars/user_18_1764678636.png', '$2y$10$VYOQGfXoxe4OqYI/jx.iKOZk4X5PMnqeYP/qvS8Kn5kqBbH1a9Upu', 'user', '2025-11-16 20:23:38', '', NULL, '', NULL, NULL, '#00ff64', NULL, 'private', NULL, 0, 1),
(19, 'AnnaV', '6_user@gmail.com', NULL, 'img/avatars/default.png', '$2y$10$IcXEFQxypzC6DGut0Vfh0uCRKeWBCc9h4a0jZVLgjE.nTtNF387Na', 'user', '2025-11-16 20:24:08', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(20, 'SerhiyP', '7_user@gmail.com', NULL, 'img/avatars/default.png', '$2y$10$n1u3tLqTZkwT9cfeTdDVwe0ezuX6N5T8JgBQRfh8gBh191ABhf4Wy', 'user', '2025-11-16 20:24:27', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(21, 'KateR', '8_user@gmail.com', NULL, 'img/avatars/default.png', '$2y$10$TN9b7A8IaRrzndXBLzhOPuKhggHglGAUa53jeenK1oCUCAthelUAm', 'user', '2025-11-16 20:24:44', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(22, 'AndriyT', '9_user@gmail.com', NULL, 'img/avatars/default.png', '$2y$10$8DT2pPuGlr.r/7IrGrZWLOv8J2.BdR3LqS8jlo/T5DsVdFWA8wkaC', 'user', '2025-11-16 20:25:05', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(24, 'Valve', '1_developer@gmail.com', NULL, 'img/avatars/user_24_1763325200.jpg', '$2y$10$sBRv2mHNIDzFcbkjRDWNl.FHPi/Zrhr2mErKQIAt/BzB1RbTn8kxS', 'developer', '2025-11-16 20:32:42', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(25, 'Mojang', '2_developer@gmail.com', NULL, 'img/avatars/user_25_1763328499.png', '$2y$10$2LpzZlVQBizROj2sAtOW0.TxoVxjg9ukwAcQu8lFZqx72Z8M4EhOO', 'developer', '2025-11-16 21:03:45', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(26, 'GIANTS Software', '3_developer@gmail.com', NULL, 'img/avatars/user_26_1763387891.jpg', '$2y$10$XNJ1EChe7oRYjeyTPBKxseMnPReq/8wexm.ShFQTeE1Wqh95VSWhK', 'user', '2025-11-17 13:57:25', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(27, 'Team Cherry Games', '4_developer@gmail.com', NULL, 'img/avatars/user_27_1764601771.jpg', '$2y$10$hRd3Hvd9jQUa99sBZ8EBGe/nFs6iMbs7iOV7DvhbQyJzTsi2ozEgy', 'developer', '2025-12-01 15:08:16', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(28, 'Capcom', '5_developer@gmail.com', NULL, 'img/avatars/user_28_1773328569.jpg', '$2y$10$5FLyvdSd7y.UtZNSAPznguAOYNU58XWML1.Nctsismx1ey6Zc.xaq', 'developer', '2026-03-12 15:15:49', '', NULL, '', '', NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(29, 'FromSoftware Inc', '6_developer@gmail.com', NULL, 'img/avatars/user_29_1774301266.jpg', '$2y$10$gAg12kBWbBHPY.myxcC.jOXizGZqdSd0XdNSgHHCIMtKy/aFAu43C', 'developer', '2026-03-23 21:27:29', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1),
(30, 'yevhen8', '7_developer@gmail.com', NULL, 'img/avatars/default.png', '$2y$10$85735WFTiWIyw7FfX8hMquISvpPguPnFac1sLIUNfsY9n4lLtzQ7.', 'developer', '2026-03-24 09:57:03', NULL, NULL, NULL, NULL, NULL, '#00ff64', NULL, 'public', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_library`
--

CREATE TABLE `user_library` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `game_id` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'owned',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rating` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user_library`
--

INSERT INTO `user_library` (`id`, `user_id`, `game_id`, `status`, `added_at`, `rating`) VALUES
(5, 2, 5, 'owned', '2025-11-12 20:41:25', NULL),
(6, 14, 10, 'owned', '2025-12-02 12:14:17', NULL),
(7, 14, 12, 'owned', '2025-12-02 12:14:24', 9),
(8, 14, 13, 'owned', '2025-12-02 12:14:28', 9),
(9, 15, 11, 'playing', '2025-12-02 12:15:44', 10),
(11, 15, 13, 'playing', '2025-12-02 12:16:00', NULL),
(12, 16, 13, 'planned', '2025-12-02 12:17:27', NULL),
(13, 16, 10, 'planned', '2025-12-02 12:17:31', NULL),
(14, 16, 9, 'completed', '2025-12-02 12:17:35', NULL),
(15, 17, 11, 'owned', '2025-12-02 12:20:11', NULL),
(16, 17, 1, 'owned', '2025-12-02 12:20:22', NULL),
(17, 17, 3, 'owned', '2025-12-02 12:20:35', NULL),
(18, 18, 13, 'owned', '2025-12-02 12:31:05', NULL),
(19, 18, 12, 'owned', '2025-12-02 12:31:16', NULL),
(20, 18, 10, 'owned', '2025-12-02 12:31:29', NULL),
(21, 24, 12, 'owned', '2025-12-02 19:13:34', NULL),
(22, 27, 13, 'owned', '2025-12-03 11:51:32', NULL),
(23, 15, 12, 'playing', '2025-12-03 13:13:14', 10),
(24, 14, 11, 'owned', '2026-02-19 09:31:01', NULL),
(25, 2, 13, 'planned', '2026-02-19 14:53:50', NULL),
(26, 2, 12, 'completed', '2026-02-19 14:53:56', NULL),
(27, 2, 11, 'playing', '2026-02-19 14:54:00', NULL),
(28, 2, 4, 'dropped', '2026-02-19 14:54:15', NULL),
(29, 24, 20, 'owned', '2026-03-12 10:16:34', NULL),
(30, 15, 25, 'playing', '2026-03-23 14:08:25', 7),
(31, 14, 25, 'completed', '2026-03-23 14:20:35', 9),
(32, 15, 17, 'playing', '2026-03-23 15:24:28', 10),
(33, 15, 10, 'dropped', '2026-03-23 17:44:58', NULL),
(34, 15, 7, 'dropped', '2026-03-23 17:44:58', NULL),
(35, 15, 5, 'completed', '2026-03-23 17:44:58', 9),
(36, 15, 6, 'completed', '2026-03-23 17:44:58', NULL),
(37, 15, 27, 'playing', '2026-03-23 21:38:27', 7),
(38, 15, 33, 'playing', '2026-03-23 21:39:12', 9),
(40, 1, 38, 'completed', '2026-03-24 10:49:08', 10);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Индексы таблицы `developer_reviews`
--
ALTER TABLE `developer_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developer_user_id` (`developer_user_id`),
  ADD KEY `author_user_id` (`author_user_id`);

--
-- Индексы таблицы `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `developer_id` (`developer_id`);

--
-- Индексы таблицы `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_friendship` (`user_id1`,`user_id2`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_game_publisher` (`publisher_id`);

--
-- Индексы таблицы `game_reviews`
--
ALTER TABLE `game_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `review_likes`
--
ALTER TABLE `review_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`review_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`);

--
-- Индексы таблицы `user_library`
--
ALTER TABLE `user_library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT для таблицы `developer_reviews`
--
ALTER TABLE `developer_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `game_reviews`
--
ALTER TABLE `game_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `review_likes`
--
ALTER TABLE `review_likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `user_library`
--
ALTER TABLE `user_library`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `developer_reviews`
--
ALTER TABLE `developer_reviews`
  ADD CONSTRAINT `developer_reviews_ibfk_1` FOREIGN KEY (`developer_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `developer_reviews_ibfk_2` FOREIGN KEY (`author_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_game_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `game_reviews`
--
ALTER TABLE `game_reviews`
  ADD CONSTRAINT `game_reviews_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `news_articles`
--
ALTER TABLE `news_articles`
  ADD CONSTRAINT `news_articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `review_likes`
--
ALTER TABLE `review_likes`
  ADD CONSTRAINT `review_likes_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `game_reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_library`
--
ALTER TABLE `user_library`
  ADD CONSTRAINT `user_library_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_library_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
