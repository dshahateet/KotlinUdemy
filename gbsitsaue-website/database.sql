-- ============================================================
-- gbsitsaue.com  —  database schema + starter content (EN / AR)
-- Import with phpMyAdmin or:  mysql -u USER -p DB_NAME < database.sql
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS settings, services, service_items, service_benefits, faqs, features,
    testimonials, team, partners, posts, offices, messages, admins;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE settings (
    skey      VARCHAR(100) NOT NULL PRIMARY KEY,
    label     VARCHAR(150) NOT NULL DEFAULT '',
    value_en  TEXT,
    value_ar  TEXT,
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL UNIQUE,
    icon VARCHAR(40) NOT NULL DEFAULT 'cloud',
    title_en VARCHAR(150) NOT NULL, title_ar VARCHAR(150) NOT NULL DEFAULT '',
    hero_title_en VARCHAR(255) NOT NULL DEFAULT '', hero_title_ar VARCHAR(255) NOT NULL DEFAULT '',
    tagline_en VARCHAR(255) NOT NULL DEFAULT '', tagline_ar VARCHAR(255) NOT NULL DEFAULT '',
    summary_en TEXT, summary_ar TEXT,
    intro_title_en VARCHAR(255) NOT NULL DEFAULT '', intro_title_ar VARCHAR(255) NOT NULL DEFAULT '',
    body_en MEDIUMTEXT, body_ar MEDIUMTEXT,
    why_text_en TEXT, why_text_ar TEXT,
    cta_title_en VARCHAR(255) NOT NULL DEFAULT '', cta_title_ar VARCHAR(255) NOT NULL DEFAULT '',
    benefits_title_en VARCHAR(255) NOT NULL DEFAULT '', benefits_title_ar VARCHAR(255) NOT NULL DEFAULT '',
    benefits_intro_en TEXT, benefits_intro_ar TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE service_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    title_en VARCHAR(150) NOT NULL, title_ar VARCHAR(150) NOT NULL DEFAULT '',
    description_en TEXT, description_ar TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE service_benefits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    icon VARCHAR(40) NOT NULL DEFAULT 'check',
    title_en VARCHAR(150) NOT NULL, title_ar VARCHAR(150) NOT NULL DEFAULT '',
    description_en TEXT, description_ar TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    question_en VARCHAR(255) NOT NULL, question_ar VARCHAR(255) NOT NULL DEFAULT '',
    answer_en TEXT, answer_ar TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(40) NOT NULL DEFAULT 'star',
    label_en VARCHAR(60) NOT NULL, label_ar VARCHAR(60) NOT NULL DEFAULT '',
    title_en VARCHAR(150) NOT NULL, title_ar VARCHAR(150) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(150) NOT NULL, name_ar VARCHAR(150) NOT NULL DEFAULT '',
    role_en VARCHAR(255) NOT NULL DEFAULT '', role_ar VARCHAR(255) NOT NULL DEFAULT '',
    quote_en TEXT, quote_ar TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE team (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(150) NOT NULL, name_ar VARCHAR(150) NOT NULL DEFAULT '',
    position_en VARCHAR(150) NOT NULL DEFAULT '', position_ar VARCHAR(150) NOT NULL DEFAULT '',
    photo VARCHAR(255) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    logo VARCHAR(255) NOT NULL DEFAULT '',
    url VARCHAR(255) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('blog','case_study') NOT NULL DEFAULT 'blog',
    slug VARCHAR(160) NOT NULL UNIQUE,
    title_en VARCHAR(255) NOT NULL, title_ar VARCHAR(255) NOT NULL DEFAULT '',
    excerpt_en TEXT, excerpt_ar TEXT,
    body_en MEDIUMTEXT, body_ar MEDIUMTEXT,
    image VARCHAR(255) NOT NULL DEFAULT '',
    published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE offices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(150) NOT NULL, title_ar VARCHAR(150) NOT NULL DEFAULT '',
    address_en TEXT, address_ar TEXT,
    phone VARCHAR(40) NOT NULL DEFAULT '',
    email VARCHAR(150) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    company VARCHAR(150) NOT NULL DEFAULT '',
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(40) NOT NULL DEFAULT '',
    service VARCHAR(100) NOT NULL DEFAULT '',
    message TEXT,
    lang CHAR(2) NOT NULL DEFAULT 'en',
    page VARCHAR(255) NOT NULL DEFAULT '',
    ip VARCHAR(45) NOT NULL DEFAULT '',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin:  username "admin"  /  password "ChangeMe@2026"  (change it after first login!)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$4.UTpMdEShGbloBZwuAwAecu5Gk2aXAn8hlRyUxBdlHiq2.WOq5ui');

-- ------------------------------------------------------------
-- Settings
-- ------------------------------------------------------------
INSERT INTO settings (skey, label, value_en, value_ar, sort_order) VALUES
('site_name',          'Site / company name',            'GBSITS', 'GBSITS', 1),
('logo_tagline',       'Small text under the logo',      'IT Solutions & Services', 'حلول وخدمات تقنية المعلومات', 2),
('meta_title',         'Home page title (SEO)',          'IT Service Provider in the UAE | GBSITS', 'مزود خدمات تقنية المعلومات في الإمارات | GBSITS', 3),
('meta_description',   'Default meta description (SEO)', 'GBSITS delivers cloud, cyber security, networking, data management and managed IT services for businesses across the UAE.', 'تقدم GBSITS خدمات الحوسبة السحابية والأمن السيبراني والشبكات وإدارة البيانات والخدمات المُدارة للشركات في دولة الإمارات.', 4),
('phone',              'Phone number',                   '+971 4 000 0000', '+971 4 000 0000', 10),
('whatsapp',           'WhatsApp number (digits only, with country code)', '97140000000', '97140000000', 11),
('address',            'Main address',                   'Business Bay, Dubai\nUnited Arab Emirates', 'الخليج التجاري، دبي\nالإمارات العربية المتحدة', 12),
('map_embed_url',      'Google Maps embed URL (optional)', '', '', 13),
('facebook',           'Facebook URL',                   '', '', 14),
('linkedin',           'LinkedIn URL',                   '', '', 15),
('hero_title',         'Home hero title',                'Enhancing Your IT Landscape', 'نرتقي ببيئة تقنية المعلومات لديك', 20),
('hero_subtitle',      'Home hero subtitle',             'From the cloud to the network edge, we design, secure and run the technology your business depends on — so your team can focus on growth.', 'من السحابة إلى أطراف الشبكة، نصمم ونؤمّن وندير التقنيات التي تعتمد عليها أعمالك، ليتفرغ فريقك للنمو.', 21),
('about_title',        'About title',                    'Your trusted IT service provider.', 'شريكك الموثوق في خدمات تقنية المعلومات.', 22),
('about_text',         'About text (paragraph 1)',       'We are a digital transformation partner helping organisations in the UAE modernise their infrastructure, protect their data and get more value from technology. Our certified engineers combine global best practice with a practical understanding of how businesses in the region operate.', 'نحن شريك في التحول الرقمي نساعد المؤسسات في دولة الإمارات على تحديث بنيتها التحتية وحماية بياناتها وتحقيق أقصى استفادة من التقنية. يجمع مهندسونا المعتمدون بين أفضل الممارسات العالمية وفهم عملي لطبيعة الأعمال في المنطقة.', 23),
('about_text_2',       'About text (paragraph 2)',       'Whether you need a single project delivered or a long-term partner to run your IT, we build solutions around your goals, your budget and your timeline.', 'سواء كنت بحاجة إلى تنفيذ مشروع واحد أو إلى شريك طويل الأمد لإدارة تقنية المعلومات لديك، فإننا نبني الحلول حول أهدافك وميزانيتك وجدولك الزمني.', 24),
('about_page_title',   'About page title',               'About Us', 'من نحن', 25),
('about_page_subtitle','About page subtitle',            'Technology partners for ambitious businesses in the UAE.', 'شركاء تقنيون للشركات الطموحة في دولة الإمارات.', 26),
('mission',            'Mission',                        'To make enterprise-grade technology simple, secure and accessible for every business we serve.', 'أن نجعل التقنيات المؤسسية المتقدمة بسيطة وآمنة ومتاحة لكل عميل نخدمه.', 27),
('vision',             'Vision',                         'To be the most trusted IT partner for organisations across the UAE and the wider region.', 'أن نكون الشريك التقني الأكثر ثقة للمؤسسات في الإمارات والمنطقة.', 28),
('values',             'Values',                         'Integrity, accountability, continuous learning and a genuine commitment to our customers\' success.', 'النزاهة، والمسؤولية، والتعلم المستمر، والالتزام الحقيقي بنجاح عملائنا.', 29),
('stat_years',         'Stat: years of experience',      '20', '20', 30),
('stat_projects',      'Stat: projects delivered',       '1000', '1000', 31),
('stat_partners',      'Stat: global partners',          '50', '50', 32),
('why_text',           'Why us — intro',                 'Long experience, certified people and a clear way of working: here is what our customers can count on.', 'خبرة طويلة، وفريق معتمد، وأسلوب عمل واضح: هذا ما يمكن لعملائنا الاعتماد عليه.', 33),
('value_text',         'Value propositions text',        'A dedicated team of professionals supports our customers around the clock, with proactive monitoring, clear SLAs and a single point of contact for every account.', 'يدعم فريقنا المتخصص عملاءنا على مدار الساعة، مع مراقبة استباقية واتفاقيات مستوى خدمة واضحة ومسؤول تواصل واحد لكل عميل.', 34),
('services_intro',     'Services page subtitle',         'End-to-end IT solutions designed, delivered and supported by certified experts.', 'حلول تقنية متكاملة يصممها وينفذها ويدعمها خبراء معتمدون.', 40),
('blog_intro',         'Blog page subtitle',             'Insights, guides and news from our technology experts.', 'رؤى وأدلة وأخبار من خبرائنا التقنيين.', 41),
('case_studies_intro', 'Case studies page subtitle',     'Real projects, real results for our customers.', 'مشاريع حقيقية ونتائج ملموسة لعملائنا.', 42),
('contact_title',      'Contact page title',             'Contact Us', 'اتصل بنا', 43),
('contact_subtitle',   'Contact page subtitle',          'Get in touch and let us help you transform your business with the right technology.', 'تواصل معنا ودعنا نساعدك على تطوير أعمالك بالتقنية المناسبة.', 44),
('contact_intro',      'Contact form intro',             'Tell us about your business and what you want to achieve. One of our consultants will get back to you within one business day.', 'أخبرنا عن أعمالك وما تريد تحقيقه، وسيتواصل معك أحد مستشارينا خلال يوم عمل واحد.', 45),
('footer_about',       'Footer short description',       'Cloud, security, networking, data and managed IT services for businesses across the UAE.', 'خدمات السحابة والأمن والشبكات والبيانات والخدمات المُدارة للشركات في دولة الإمارات.', 46);

-- ------------------------------------------------------------
-- Services
-- ------------------------------------------------------------
INSERT INTO services (id, slug, icon, title_en, title_ar, hero_title_en, hero_title_ar, tagline_en, tagline_ar, summary_en, summary_ar, intro_title_en, intro_title_ar, body_en, body_ar, why_text_en, why_text_ar, cta_title_en, cta_title_ar, benefits_title_en, benefits_title_ar, benefits_intro_en, benefits_intro_ar, sort_order) VALUES
(1, 'cloud-solutions', 'cloud',
 'Cloud Solutions', 'الحلول السحابية',
 'Certified Cloud Solutions Provider in the UAE', 'مزود معتمد للحلول السحابية في الإمارات',
 'Scalability | Cost-effectiveness | Security | Flexibility', 'قابلية التوسع | خفض التكاليف | الأمان | المرونة',
 'Move, modernise and manage your workloads on public, private and hybrid clouds with a partner that handles every step.', 'انقل أعمالك وطوّرها وأدرها على السحابة العامة أو الخاصة أو الهجينة مع شريك يتولى كل خطوة.',
 'What is Cloud Computing?', 'ما هي الحوسبة السحابية؟',
 '<p>Cloud computing gives your business on-demand access to servers, storage, databases, software and analytics over the internet — paying only for what you use instead of buying and maintaining hardware.</p><p>We work with leading platforms such as Microsoft Azure, Amazon Web Services and well-known backup and recovery vendors to design an environment that fits your applications, compliance needs and budget.</p><p>Our team plans the migration, integrates it with your existing workflows and keeps it running smoothly afterwards, so your people get secure access to their data from anywhere.</p>',
 '<p>تمنح الحوسبة السحابية شركتك وصولاً فورياً إلى الخوادم والتخزين وقواعد البيانات والبرمجيات وأدوات التحليل عبر الإنترنت، مع الدفع مقابل ما تستخدمه فقط بدلاً من شراء الأجهزة وصيانتها.</p><p>نعمل مع منصات رائدة مثل Microsoft Azure و Amazon Web Services ومزودي حلول النسخ الاحتياطي والاستعادة المعروفين لتصميم بيئة تناسب تطبيقاتك ومتطلبات الامتثال وميزانيتك.</p><p>يخطط فريقنا لعملية الانتقال، ويدمجها مع سير العمل الحالي، ويواصل إدارتها بعد ذلك، ليتمكن موظفوك من الوصول الآمن إلى بياناتهم من أي مكان.</p>',
 'Our cloud architects hold current certifications from the major platforms and have migrated workloads of every size. We focus on security and cost control from day one, so the cloud delivers the savings it promises.', 'يحمل مهندسو السحابة لدينا شهادات حديثة من المنصات الكبرى ولديهم خبرة في نقل أنظمة بمختلف الأحجام. نركز على الأمان وضبط التكاليف من اليوم الأول لتحقق السحابة التوفير المنشود.',
 'From setup to backup, we have got you covered!', 'من الإعداد حتى النسخ الاحتياطي، نحن معك في كل خطوة!',
 'Why move to the cloud?', 'لماذا تنتقل إلى السحابة؟',
 'Here is what businesses in the UAE gain when they work with the right cloud partner.', 'إليك ما تكسبه الشركات في الإمارات عند العمل مع الشريك السحابي المناسب.',
 1),
(2, 'cyber-security', 'shield',
 'Cyber Security', 'الأمن السيبراني',
 'Cyber Security Solutions for UAE Businesses', 'حلول الأمن السيبراني للشركات في الإمارات',
 'Protect | Detect | Respond | Recover', 'الحماية | الكشف | الاستجابة | التعافي',
 'Layered protection for your users, devices, networks and data — backed by monitoring and rapid incident response.', 'حماية متعددة الطبقات للمستخدمين والأجهزة والشبكات والبيانات، مدعومة بالمراقبة والاستجابة السريعة للحوادث.',
 'A security strategy built around your risks', 'استراتيجية أمنية مبنية حول المخاطر الخاصة بك',
 '<p>Cyber threats change every day. A firewall alone is no longer enough — organisations need visibility across endpoints, email, cloud applications and the network, along with people who know how to act when something goes wrong.</p><p>We start with an assessment of your current posture, then design and implement the right mix of controls: next-generation firewalls, endpoint detection and response, identity protection, email security and security awareness training.</p><p>Our solutions also help you meet local and international regulatory requirements, with clear reporting for management and auditors.</p>',
 '<p>تتغير التهديدات السيبرانية يومياً، ولم يعد جدار الحماية وحده كافياً. تحتاج المؤسسات إلى رؤية شاملة للأجهزة الطرفية والبريد الإلكتروني والتطبيقات السحابية والشبكة، وإلى فريق يعرف كيف يتصرف عند وقوع أي حادث.</p><p>نبدأ بتقييم وضعك الأمني الحالي، ثم نصمم وننفذ المزيج المناسب من الضوابط: جدران الحماية من الجيل التالي، والكشف والاستجابة على الأجهزة الطرفية، وحماية الهويات، وأمن البريد الإلكتروني، والتوعية الأمنية للموظفين.</p><p>كما تساعدك حلولنا على تلبية المتطلبات التنظيمية المحلية والدولية، مع تقارير واضحة للإدارة والمدققين.</p>',
 'We combine certified security engineers, proven technology partners and a practical, business-first approach. Whether you are a small company or a large enterprise, we scale protection to your needs.', 'نجمع بين مهندسي أمن معتمدين وشركاء تقنيين موثوقين ونهج عملي يضع أعمالك أولاً. سواء كنت شركة صغيرة أو مؤسسة كبيرة، نصمم الحماية بما يناسب احتياجاتك.',
 'Stay one step ahead of cyber threats', 'كن متقدماً بخطوة على التهديدات السيبرانية',
 'Why invest in cyber security?', 'لماذا تستثمر في الأمن السيبراني؟',
 'Strong security protects your reputation, your customers and your bottom line.', 'الأمن القوي يحمي سمعتك وعملاءك وأرباحك.',
 2),
(3, 'enterprise-networking', 'network',
 'Enterprise Networking', 'شبكات المؤسسات',
 'Enterprise Networking Solutions', 'حلول شبكات المؤسسات',
 'Reliable | Fast | Secure | Scalable', 'موثوقة | سريعة | آمنة | قابلة للتوسع',
 'Wired, wireless and SD-WAN networks designed for performance, security and easy management across all your sites.', 'شبكات سلكية ولاسلكية و SD-WAN مصممة للأداء والأمان وسهولة الإدارة في جميع مواقعك.',
 'The backbone of your business', 'العمود الفقري لأعمالك',
 '<p>Every application, call and transaction depends on the network. We design and build enterprise networks that are fast, resilient and simple to operate — from structured cabling and data centre switching to campus Wi-Fi and secure connections between branches.</p><p>Our engineers survey your sites, plan capacity for growth and deploy equipment from leading vendors, then document everything so your network stays easy to manage.</p>',
 '<p>يعتمد كل تطبيق ومكالمة ومعاملة على الشبكة. نصمم وننفذ شبكات مؤسسية سريعة ومرنة وسهلة التشغيل، من الكابلات الهيكلية ومحولات مراكز البيانات إلى شبكات الواي فاي والاتصال الآمن بين الفروع.</p><p>يقوم مهندسونا بمسح مواقعك، والتخطيط للسعة المستقبلية، وتركيب المعدات من أفضل المصنعين، ثم توثيق كل شيء لتبقى شبكتك سهلة الإدارة.</p>',
 'We have delivered networks for offices, hotels, factories and multi-site organisations. Our designs follow industry standards and are built to grow with you.', 'نفذنا شبكات لمكاتب وفنادق ومصانع ومؤسسات متعددة الفروع. تتبع تصاميمنا المعايير العالمية ومبنية لتنمو مع أعمالك.',
 'Build a network you can rely on', 'ابنِ شبكة يمكنك الاعتماد عليها',
 'Benefits of a modern network', 'فوائد الشبكة الحديثة',
 'A well-designed network reduces downtime and supports every other technology investment.', 'الشبكة المصممة جيداً تقلل الأعطال وتدعم كل استثمار تقني آخر.',
 3),
(4, 'enterprise-data-management', 'database',
 'Enterprise Data Management', 'إدارة بيانات المؤسسات',
 'Enterprise Data Management Solutions', 'حلول إدارة بيانات المؤسسات',
 'Store | Protect | Analyse | Comply', 'التخزين | الحماية | التحليل | الامتثال',
 'Storage, backup, recovery and analytics that keep your data available, protected and useful for decision-making.', 'حلول التخزين والنسخ الاحتياطي والاستعادة والتحليل التي تبقي بياناتك متاحة ومحمية ومفيدة لاتخاذ القرار.',
 'Turn your data into an asset', 'حوّل بياناتك إلى أصل ذي قيمة',
 '<p>Data grows every day, and so does the cost of losing it. We help you organise where data lives, protect it with reliable backup and disaster recovery, and make it available to the people and systems that need it.</p><p>From primary storage and virtualisation to business continuity planning and reporting dashboards, our solutions give you control and confidence over your information.</p>',
 '<p>تنمو البيانات كل يوم، وتنمو معها تكلفة فقدانها. نساعدك على تنظيم أماكن تخزين البيانات، وحمايتها بنسخ احتياطي موثوق وخطط للتعافي من الكوارث، وإتاحتها للأشخاص والأنظمة التي تحتاجها.</p><p>من التخزين الأساسي والمحاكاة الافتراضية إلى تخطيط استمرارية الأعمال ولوحات التقارير، تمنحك حلولنا السيطرة والثقة في معلوماتك.</p>',
 'Our specialists design data platforms around clear recovery objectives and regulatory needs, and test them regularly so you know they work.', 'يصمم متخصصونا منصات البيانات وفق أهداف استعادة واضحة ومتطلبات تنظيمية، ويختبرونها بانتظام لتتأكد من فعاليتها.',
 'Keep your data safe and available', 'حافظ على بياناتك آمنة ومتاحة',
 'Why manage your data properly?', 'لماذا تدير بياناتك بشكل صحيح؟',
 'Good data management lowers risk and unlocks better decisions.', 'الإدارة الجيدة للبيانات تقلل المخاطر وتتيح قرارات أفضل.',
 4),
(5, 'it-managed-services', 'headset',
 'IT Managed Services', 'خدمات تقنية المعلومات المُدارة',
 'Managed IT Service Provider in the UAE', 'مزود خدمات تقنية المعلومات المُدارة في الإمارات',
 'Proactive | Predictable | 24/7 | Expert', 'استباقية | تكلفة واضحة | على مدار الساعة | خبرة',
 'Let our team monitor, maintain and support your IT around the clock for a predictable monthly cost.', 'دع فريقنا يراقب ويصون ويدعم أنظمتك على مدار الساعة مقابل تكلفة شهرية واضحة.',
 'Your IT department, extended', 'قسم تقنية المعلومات لديك، بقدرات أكبر',
 '<p>Managed services give you a complete IT team without the cost of building one. We monitor your servers, network, cloud services and devices, apply updates, fix issues before they affect users and provide a helpdesk your staff can call at any time.</p><p>Each customer gets clear service levels, regular reports and a dedicated account manager who understands their business.</p>',
 '<p>تمنحك الخدمات المُدارة فريق تقنية معلومات متكاملاً دون تكلفة بنائه. نراقب الخوادم والشبكة والخدمات السحابية والأجهزة، ونطبق التحديثات، ونعالج المشكلات قبل أن تؤثر على المستخدمين، ونوفر مكتب دعم يمكن لموظفيك الاتصال به في أي وقت.</p><p>يحصل كل عميل على مستويات خدمة واضحة وتقارير دورية ومدير حساب مخصص يفهم طبيعة أعماله.</p>',
 'Our support team works around the clock with proven processes and tools, so problems are solved quickly and your costs stay predictable.', 'يعمل فريق الدعم لدينا على مدار الساعة بإجراءات وأدوات مجربة، لتُحل المشكلات بسرعة وتبقى تكاليفك متوقعة.',
 'Focus on your business — we will handle the IT', 'ركّز على أعمالك ونحن نتولى تقنية المعلومات',
 'Benefits of managed IT services', 'فوائد الخدمات المُدارة',
 'Predictable costs, fewer outages and expert help whenever you need it.', 'تكاليف متوقعة، وأعطال أقل، ومساعدة الخبراء متى احتجت إليها.',
 5);

INSERT INTO service_items (service_id, title_en, title_ar, description_en, description_ar, sort_order) VALUES
(1, 'Assessment', 'التقييم', 'A full review of your current infrastructure and applications to build a clear, costed cloud roadmap.', 'مراجعة شاملة للبنية التحتية والتطبيقات الحالية لبناء خارطة طريق سحابية واضحة ومحددة التكاليف.', 1),
(1, 'Microsoft Solutions', 'حلول مايكروسوفت', 'Microsoft 365, Azure and Dynamics solutions deployed and configured for the way your teams work.', 'تنفيذ وتهيئة حلول Microsoft 365 و Azure و Dynamics بما يناسب طريقة عمل فرقك.', 2),
(1, 'Deployment & Migration', 'النشر والترحيل', 'Planned, low-risk migration of servers, data and applications with minimal disruption.', 'ترحيل مخطط ومنخفض المخاطر للخوادم والبيانات والتطبيقات بأقل قدر من التعطيل.', 3),
(1, 'Backup & Security', 'النسخ الاحتياطي والأمان', 'Reliable cloud backup and security controls that keep your information protected and recoverable.', 'نسخ احتياطي سحابي موثوق وضوابط أمنية تحافظ على معلوماتك محمية وقابلة للاستعادة.', 4),
(1, 'Operations & Management', 'التشغيل والإدارة', 'Proactive monitoring and management of your cloud environment for performance and availability.', 'مراقبة وإدارة استباقية لبيئتك السحابية لضمان الأداء والتوافر.', 5),
(1, 'Cloud Optimisation', 'تحسين السحابة', 'We find waste, right-size resources and tune performance to lower your monthly bill.', 'نكتشف الهدر ونضبط الموارد ونحسن الأداء لتخفيض فاتورتك الشهرية.', 6),
(2, 'Security Assessment', 'التقييم الأمني', 'Vulnerability scanning and risk assessment that show exactly where you are exposed.', 'فحص الثغرات وتقييم المخاطر لمعرفة نقاط الضعف بدقة.', 1),
(2, 'Network & Firewall Security', 'أمن الشبكات وجدران الحماية', 'Next-generation firewalls, secure remote access and segmentation for your network.', 'جدران حماية من الجيل التالي، ووصول آمن عن بُعد، وتقسيم الشبكة.', 2),
(2, 'Endpoint Protection', 'حماية الأجهزة الطرفية', 'Detection and response on laptops, servers and mobile devices to stop modern threats.', 'الكشف والاستجابة على الحواسيب والخوادم والأجهزة المحمولة لإيقاف التهديدات الحديثة.', 3),
(2, 'Email & Identity Security', 'أمن البريد والهويات', 'Anti-phishing, multi-factor authentication and identity protection for every user.', 'مكافحة التصيد والمصادقة متعددة العوامل وحماية الهوية لكل مستخدم.', 4),
(2, 'Security Awareness', 'التوعية الأمنية', 'Practical training and simulations that turn your staff into a strong line of defence.', 'تدريب عملي ومحاكاة تجعل موظفيك خط دفاع قوياً.', 5),
(2, 'Compliance & Governance', 'الامتثال والحوكمة', 'Policies, controls and reporting aligned with local and international standards.', 'سياسات وضوابط وتقارير متوافقة مع المعايير المحلية والدولية.', 6),
(3, 'Structured Cabling', 'الكابلات الهيكلية', 'Copper and fibre cabling designed, installed and certified to international standards.', 'تصميم وتركيب واعتماد كابلات النحاس والألياف الضوئية وفق المعايير الدولية.', 1),
(3, 'Switching & Routing', 'المحولات والموجهات', 'Core, distribution and access layers built for speed and resilience.', 'طبقات أساسية وتوزيع ووصول مبنية للسرعة والموثوقية.', 2),
(3, 'Wireless Networks', 'الشبكات اللاسلكية', 'Enterprise Wi-Fi with full coverage, guest access and central management.', 'شبكات واي فاي مؤسسية بتغطية كاملة ووصول للزوار وإدارة مركزية.', 3),
(3, 'SD-WAN', 'SD-WAN', 'Secure, high-performance connectivity between your branches and the cloud.', 'اتصال آمن وعالي الأداء بين فروعك والسحابة.', 4),
(4, 'Storage & Virtualisation', 'التخزين والمحاكاة الافتراضية', 'Modern storage and virtual infrastructure that scale with your workloads.', 'تخزين حديث وبنية افتراضية تتوسع مع أعمالك.', 1),
(4, 'Backup & Disaster Recovery', 'النسخ الاحتياطي والتعافي من الكوارث', 'Tested backup and recovery plans that bring you back online fast.', 'خطط نسخ احتياطي واستعادة مُختبرة تعيدك إلى العمل بسرعة.', 2),
(4, 'Business Continuity', 'استمرارية الأعمال', 'Planning and technology to keep critical services running during disruption.', 'تخطيط وتقنيات تبقي الخدمات الحيوية تعمل أثناء الأزمات.', 3),
(4, 'Data Analytics', 'تحليل البيانات', 'Dashboards and reporting that turn raw data into clear business insight.', 'لوحات معلومات وتقارير تحول البيانات الخام إلى رؤى واضحة.', 4),
(5, '24/7 Monitoring', 'مراقبة على مدار الساعة', 'Continuous monitoring of servers, network and cloud with automatic alerts.', 'مراقبة مستمرة للخوادم والشبكة والسحابة مع تنبيهات تلقائية.', 1),
(5, 'Helpdesk Support', 'مكتب الدعم الفني', 'Fast, friendly support for your users by phone, email and remote session.', 'دعم سريع وودود لموظفيك عبر الهاتف والبريد والاتصال عن بُعد.', 2),
(5, 'Patch & Update Management', 'إدارة التحديثات', 'Systems kept up to date and secure according to an agreed schedule.', 'إبقاء الأنظمة محدثة وآمنة وفق جدول متفق عليه.', 3),
(5, 'IT Consulting', 'الاستشارات التقنية', 'Strategic advice and planning to align technology with your business goals.', 'استشارات وتخطيط استراتيجي لمواءمة التقنية مع أهداف أعمالك.', 4);

INSERT INTO service_benefits (service_id, icon, title_en, title_ar, description_en, description_ar, sort_order) VALUES
(1, 'wallet', 'Cost-Effective', 'تكلفة أقل', 'Replace up-front hardware spending with flexible, pay-as-you-go pricing.', 'استبدل الإنفاق المسبق على الأجهزة بتسعير مرن حسب الاستخدام.', 1),
(1, 'lock', 'Robust Security', 'أمان قوي', 'Enterprise-grade security controls protect your data in the cloud.', 'ضوابط أمنية بمستوى المؤسسات تحمي بياناتك في السحابة.', 2),
(1, 'layers', 'Always Up to Date', 'تحديث دائم', 'Modern infrastructure without the burden of maintaining it yourself.', 'بنية تحتية حديثة دون عبء صيانتها بنفسك.', 3),
(1, 'trending', 'Scalable Growth', 'نمو قابل للتوسع', 'Add capacity in minutes as your business grows across the region.', 'أضف السعة خلال دقائق مع توسع أعمالك في المنطقة.', 4),
(2, 'shield', 'Reduced Risk', 'مخاطر أقل', 'Stop attacks before they reach your critical systems.', 'أوقف الهجمات قبل وصولها إلى أنظمتك الحيوية.', 1),
(2, 'report', 'Regulatory Compliance', 'الامتثال التنظيمي', 'Meet the requirements of regulators, partners and customers.', 'التزم بمتطلبات الجهات التنظيمية والشركاء والعملاء.', 2),
(2, 'clock', 'Faster Response', 'استجابة أسرع', 'Detect and contain incidents quickly to limit their impact.', 'اكتشف الحوادث واحتوِها بسرعة للحد من تأثيرها.', 3),
(2, 'users', 'Customer Trust', 'ثقة العملاء', 'Show customers that their information is in safe hands.', 'أظهر لعملائك أن معلوماتهم في أيدٍ أمينة.', 4),
(3, 'clock', 'Less Downtime', 'أعطال أقل', 'Redundant designs keep your business connected.', 'تصاميم احتياطية تبقي أعمالك متصلة دائماً.', 1),
(3, 'trending', 'Better Performance', 'أداء أفضل', 'Faster applications and a better experience for users.', 'تطبيقات أسرع وتجربة أفضل للمستخدمين.', 2),
(3, 'settings', 'Simple Management', 'إدارة مبسطة', 'Central visibility and control across all sites.', 'رؤية وتحكم مركزي في جميع المواقع.', 3),
(3, 'lock', 'Built-in Security', 'أمان مدمج', 'Security designed into the network from the start.', 'أمان مدمج في تصميم الشبكة منذ البداية.', 4),
(4, 'lock', 'Protected Data', 'بيانات محمية', 'Recover quickly from hardware failure, mistakes or ransomware.', 'استعد بياناتك بسرعة بعد أعطال الأجهزة أو الأخطاء أو هجمات الفدية.', 1),
(4, 'report', 'Better Decisions', 'قرارات أفضل', 'Reliable data and reporting for management.', 'بيانات وتقارير موثوقة للإدارة.', 2),
(4, 'wallet', 'Lower Storage Costs', 'تكاليف تخزين أقل', 'Store data on the right tier at the right price.', 'خزّن البيانات في المستوى المناسب وبالسعر المناسب.', 3),
(4, 'shield', 'Compliance Ready', 'جاهزية للامتثال', 'Retention and protection policies that satisfy auditors.', 'سياسات احتفاظ وحماية ترضي المدققين.', 4),
(5, 'wallet', 'Predictable Costs', 'تكاليف متوقعة', 'One clear monthly fee instead of surprise repair bills.', 'رسوم شهرية واضحة بدلاً من فواتير إصلاح مفاجئة.', 1),
(5, 'clock', 'Round-the-Clock Care', 'رعاية على مدار الساعة', 'Problems are detected and fixed at any hour.', 'تُكتشف المشكلات وتُعالج في أي ساعة.', 2),
(5, 'users', 'Expert Team', 'فريق خبراء', 'Access a wide range of skills without hiring.', 'احصل على مجموعة واسعة من المهارات دون توظيف.', 3),
(5, 'trending', 'Focus on Growth', 'تركيز على النمو', 'Free your staff to work on what matters most.', 'فرّغ موظفيك للعمل على ما يهم أكثر.', 4);

INSERT INTO faqs (service_id, question_en, question_ar, answer_en, answer_ar, sort_order) VALUES
(1, 'How long does a cloud migration take?', 'كم تستغرق عملية الانتقال إلى السحابة؟', 'It depends on the number of systems and the amount of data. Small migrations can be completed in days; larger projects are delivered in planned phases over several weeks.', 'يعتمد ذلك على عدد الأنظمة وحجم البيانات. يمكن إنجاز عمليات الانتقال الصغيرة خلال أيام، أما المشاريع الأكبر فتُنفذ على مراحل مخططة خلال عدة أسابيع.', 1),
(1, 'Is my data safe in the cloud?', 'هل بياناتي آمنة في السحابة؟', 'Yes, when the environment is configured correctly. We apply encryption, access control, backup and monitoring as standard on every project.', 'نعم، عند تهيئة البيئة بشكل صحيح. نطبق التشفير والتحكم في الوصول والنسخ الاحتياطي والمراقبة بشكل أساسي في كل مشروع.', 2),
(1, 'Can you host data inside the UAE?', 'هل يمكن استضافة البيانات داخل الإمارات؟', 'Yes. Major cloud providers operate data centres in the UAE, and we can design your environment to keep data in-country where required.', 'نعم. تمتلك كبرى شركات السحابة مراكز بيانات داخل الإمارات، ويمكننا تصميم بيئتك لإبقاء البيانات داخل الدولة عند الحاجة.', 3),
(2, 'Where should we start with cyber security?', 'من أين نبدأ في الأمن السيبراني؟', 'Start with an assessment. It shows your biggest risks so you can invest first where it matters most.', 'ابدأ بالتقييم الأمني، فهو يوضح أكبر المخاطر لتستثمر أولاً حيث الحاجة أكبر.', 1),
(2, 'Do you offer solutions for small businesses?', 'هل تقدمون حلولاً للشركات الصغيرة؟', 'Yes. We offer packaged protection for small and medium businesses as well as advanced programmes for large enterprises.', 'نعم. نقدم باقات حماية للشركات الصغيرة والمتوسطة، إضافة إلى برامج متقدمة للمؤسسات الكبيرة.', 2),
(3, 'Can you upgrade our network without downtime?', 'هل يمكن تحديث الشبكة دون توقف العمل؟', 'In most cases, yes. We plan cut-overs outside business hours and keep a rollback plan ready.', 'في معظم الحالات نعم. نخطط للتحويل خارج ساعات العمل ونجهز خطة للتراجع عند الحاجة.', 1),
(4, 'How often should backups be tested?', 'كم مرة يجب اختبار النسخ الاحتياطية؟', 'We recommend testing restores at least every quarter, and after any major change to your systems.', 'ننصح باختبار الاستعادة مرة كل ثلاثة أشهر على الأقل، وبعد أي تغيير كبير في الأنظمة.', 1),
(5, 'What is included in managed services?', 'ماذا تشمل الخدمات المُدارة؟', 'Monitoring, maintenance, helpdesk support, security updates and reporting, tailored to the systems you choose to include.', 'المراقبة والصيانة ومكتب الدعم والتحديثات الأمنية والتقارير، بحسب الأنظمة التي تختار إدراجها.', 1),
(5, 'Do you support on-site visits?', 'هل تقدمون زيارات ميدانية؟', 'Yes. Most issues are solved remotely, and engineers visit on site whenever needed.', 'نعم. تُحل معظم المشكلات عن بُعد، ويزور المهندسون الموقع عند الحاجة.', 2);

-- ------------------------------------------------------------
-- Why us (features)
-- ------------------------------------------------------------
INSERT INTO features (icon, label_en, label_ar, title_en, title_ar, sort_order) VALUES
('globe',   'Culture',    'الثقافة',     'Deep understanding of the local market & industry trends', 'فهم عميق للسوق المحلي واتجاهات القطاع', 1),
('award',   'Awards',     'الجوائز',     'Certified, award-winning organisation', 'مؤسسة معتمدة وحائزة على جوائز', 2),
('wallet',  'Billing',    'الفوترة',     'Transparent billing models', 'نماذج فوترة شفافة', 3),
('clock',   'Efficiency', 'الكفاءة',     'Cost- and time-efficient project delivery', 'تنفيذ المشاريع بكفاءة في الوقت والتكلفة', 4),
('users',   'Team',       'الفريق',      'A team of certified experts', 'فريق من الخبراء المعتمدين', 5),
('report',  'Reports',    'التقارير',    'Transparent operations with timely reports', 'عمليات شفافة وتقارير في وقتها', 6),
('headset', '24/7',       '24/7',        'Responsive team for client management', 'فريق سريع الاستجابة لخدمة العملاء', 7),
('star',    'Unique',     'التميز',      'Tailor-made solution designs', 'حلول مصممة خصيصاً لاحتياجاتك', 8);

-- ------------------------------------------------------------
-- Testimonials & leadership: replace with your real data from the admin panel,
-- then switch "is_active" on. They stay hidden until you do.
-- ------------------------------------------------------------
INSERT INTO testimonials (name_en, name_ar, role_en, role_ar, quote_en, quote_ar, sort_order, is_active) VALUES
('Client name', 'اسم العميل', 'Position, Company', 'المنصب، الشركة', 'Write your client''s testimonial here.', 'اكتب شهادة العميل هنا.', 1, 0),
('Client name', 'اسم العميل', 'Position, Company', 'المنصب، الشركة', 'Write your client''s testimonial here.', 'اكتب شهادة العميل هنا.', 2, 0),
('Client name', 'اسم العميل', 'Position, Company', 'المنصب، الشركة', 'Write your client''s testimonial here.', 'اكتب شهادة العميل هنا.', 3, 0);

INSERT INTO team (name_en, name_ar, position_en, position_ar, sort_order, is_active) VALUES
('Full name', 'الاسم الكامل', 'Member of the Board of Directors', 'عضو مجلس الإدارة', 1, 0),
('Full name', 'الاسم الكامل', 'Chief Executive Officer', 'الرئيس التنفيذي', 2, 0),
('Full name', 'الاسم الكامل', 'Business Director', 'مدير الأعمال', 3, 0);

-- Partners are shown as text until you upload a logo for each one in the admin panel.
INSERT INTO partners (name, sort_order, is_active) VALUES
('Microsoft', 1, 0), ('AWS', 2, 0), ('Cisco', 3, 0), ('Fortinet', 4, 0), ('Dell Technologies', 5, 0), ('Veeam', 6, 0);

INSERT INTO offices (title_en, title_ar, address_en, address_ar, phone, email, sort_order) VALUES
('United Arab Emirates', 'الإمارات العربية المتحدة', 'Business Bay, Dubai\nUnited Arab Emirates', 'الخليج التجاري، دبي\nالإمارات العربية المتحدة', '+971 4 000 0000', 'info@gbsitsaue.com', 1);

-- ------------------------------------------------------------
-- Sample posts
-- ------------------------------------------------------------
INSERT INTO posts (type, slug, title_en, title_ar, excerpt_en, excerpt_ar, body_en, body_ar, published_at) VALUES
('blog', 'five-steps-to-a-safer-business',
 '5 Practical Steps to a Safer Business', '5 خطوات عملية لحماية أعمالك',
 'Simple measures every company in the UAE can take this month to reduce cyber risk.', 'إجراءات بسيطة يمكن لأي شركة في الإمارات اتخاذها هذا الشهر لتقليل المخاطر السيبرانية.',
 '<p>Most successful attacks exploit basic gaps. Here are five steps that make a real difference:</p><ol><li>Turn on multi-factor authentication for email and remote access.</li><li>Keep operating systems and applications updated.</li><li>Back up important data and test restoring it.</li><li>Train staff to recognise phishing emails.</li><li>Limit administrator rights to the people who really need them.</li></ol><p>Need help putting these in place? Contact our team for a free consultation.</p>',
 '<p>تستغل معظم الهجمات الناجحة ثغرات بسيطة. إليك خمس خطوات تُحدث فرقاً حقيقياً:</p><ol><li>فعّل المصادقة متعددة العوامل للبريد الإلكتروني والوصول عن بُعد.</li><li>حدّث أنظمة التشغيل والتطبيقات باستمرار.</li><li>انسخ بياناتك المهمة احتياطياً واختبر استعادتها.</li><li>درّب الموظفين على تمييز رسائل التصيد.</li><li>اقصر صلاحيات المسؤول على من يحتاجها فعلاً.</li></ol><p>هل تحتاج مساعدة في تطبيق ذلك؟ تواصل مع فريقنا للحصول على استشارة مجانية.</p>',
 '2026-10-01 10:00:00'),
('case_study', 'hybrid-cloud-for-a-growing-retailer',
 'Hybrid Cloud for a Growing Retailer', 'سحابة هجينة لشركة تجزئة متنامية',
 'How a multi-branch retailer cut infrastructure costs and improved uptime with a hybrid cloud design.', 'كيف خفّضت شركة تجزئة متعددة الفروع تكاليف البنية التحتية ورفعت جاهزية أنظمتها بتصميم سحابي هجين.',
 '<h3>The challenge</h3><p>Ageing on-premise servers, slow branch connections and no tested disaster recovery plan.</p><h3>Our solution</h3><p>We moved key workloads to the cloud, connected branches with SD-WAN and implemented automated backup with a documented recovery plan.</p><h3>The result</h3><p>Lower running costs, faster applications in every branch and the confidence that data can be recovered quickly.</p><p><em>Replace this sample with your own case study from the admin panel.</em></p>',
 '<h3>التحدي</h3><p>خوادم محلية قديمة، واتصالات بطيئة بين الفروع، وعدم وجود خطة مُختبرة للتعافي من الكوارث.</p><h3>الحل</h3><p>نقلنا الأنظمة الرئيسية إلى السحابة، وربطنا الفروع بتقنية SD-WAN، وطبقنا نسخاً احتياطياً تلقائياً مع خطة استعادة موثقة.</p><h3>النتيجة</h3><p>تكاليف تشغيل أقل، وتطبيقات أسرع في كل فرع، وثقة بإمكانية استعادة البيانات بسرعة.</p><p><em>استبدل هذا المثال بدراسة حالة حقيقية من لوحة التحكم.</em></p>',
 '2026-09-15 10:00:00');
