--
-- PostgreSQL database dump
--

\restrict 5ULTiAuIsyv77BWzz4CFgQtEcEb94JgyUtychhIj0cYBZskwWmljX3tHhlGVafT

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO sail;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO sail;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    prefix character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.categories OWNER TO sail;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_id_seq OWNER TO sail;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO sail;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO sail;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: items; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.items (
    id bigint NOT NULL,
    category_id bigint NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    price numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.items OWNER TO sail;

--
-- Name: items_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.items_id_seq OWNER TO sail;

--
-- Name: items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.items_id_seq OWNED BY public.items.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO sail;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO sail;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO sail;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO sail;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO sail;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO sail;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO sail;

--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO sail;

--
-- Name: payments; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.payments (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    sale_id bigint NOT NULL,
    amount_paid numeric(15,2) NOT NULL,
    payment_status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT payments_payment_status_check CHECK (((payment_status)::text = ANY ((ARRAY['Lunas'::character varying, 'Belum Lunas'::character varying])::text[])))
);


ALTER TABLE public.payments OWNER TO sail;

--
-- Name: payments_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.payments_id_seq OWNER TO sail;

--
-- Name: payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.payments_id_seq OWNED BY public.payments.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO sail;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO sail;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO sail;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO sail;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO sail;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sale_items; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.sale_items (
    id bigint NOT NULL,
    sale_id bigint NOT NULL,
    item_id bigint NOT NULL,
    quantity integer NOT NULL,
    price numeric(15,2) NOT NULL,
    subtotal numeric(15,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.sale_items OWNER TO sail;

--
-- Name: sale_items_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.sale_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sale_items_id_seq OWNER TO sail;

--
-- Name: sale_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.sale_items_id_seq OWNED BY public.sale_items.id;


--
-- Name: sales; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.sales (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    total_price numeric(15,2) NOT NULL,
    payment_status character varying(255) DEFAULT 'Belum Dibayar'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT sales_payment_status_check CHECK (((payment_status)::text = ANY ((ARRAY['Belum Dibayar'::character varying, 'Dibayar Sebagian'::character varying, 'Sudah Dibayar'::character varying])::text[])))
);


ALTER TABLE public.sales OWNER TO sail;

--
-- Name: sales_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.sales_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sales_id_seq OWNER TO sail;

--
-- Name: sales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.sales_id_seq OWNED BY public.sales.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO sail;

--
-- Name: users; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO sail;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO sail;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: items id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.items ALTER COLUMN id SET DEFAULT nextval('public.items_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: sale_items id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sale_items ALTER COLUMN id SET DEFAULT nextval('public.sale_items_id_seq'::regclass);


--
-- Name: sales id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sales ALTER COLUMN id SET DEFAULT nextval('public.sales_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-spatie.permission.cache	a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:17:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:9:"user-list";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:11:"user-create";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:9:"user-edit";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:11:"user-delete";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:9:"item-list";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:11:"item-create";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:9:"item-edit";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:11:"item-delete";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:14:"setting-manage";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:9:"sale-list";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:11:"sale-create";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:9:"sale-edit";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:11:"sale-delete";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:12:"payment-list";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:14:"payment-create";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:12:"payment-edit";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:14:"payment-delete";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}}s:5:"roles";a:3:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:5:"Admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:10:"Supervisor";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:3;s:1:"b";s:5:"Staff";s:1:"c";s:3:"web";}}}	1778647256
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.categories (id, name, prefix, created_at, updated_at) FROM stdin;
1	Electronics	EL	2026-05-12 04:40:40	2026-05-12 04:40:40
2	Fashion	FS	2026-05-12 04:40:40	2026-05-12 04:40:40
3	Food	FD	2026-05-12 04:40:40	2026-05-12 04:40:40
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: items; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.items (id, category_id, code, name, price, created_at, updated_at) FROM stdin;
1	1	EL-0001	Smartphone	5000000.00	2026-05-12 04:40:40	2026-05-12 04:40:40
2	1	EL-0002	Laptop	12000000.00	2026-05-12 04:40:40	2026-05-12 04:40:40
3	2	FS-0001	T-Shirt	150000.00	2026-05-12 04:40:40	2026-05-12 04:40:40
4	2	FS-0002	Jeans	300000.00	2026-05-12 04:40:40	2026-05-12 04:40:40
5	3	FD-0001	Coffee	50000.00	2026-05-12 04:40:40	2026-05-12 04:40:40
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_05_10_140000_create_categories_table	1
5	2026_05_10_141600_create_items_table	1
6	2026_05_10_141601_create_permission_tables	1
7	2026_05_11_090000_create_sales_table	1
8	2026_05_11_090001_create_sale_items_table	1
9	2026_05_11_100000_create_payments_table	1
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	1
2	App\\Models\\User	2
3	App\\Models\\User	3
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: payments; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.payments (id, code, sale_id, amount_paid, payment_status, created_at, updated_at) FROM stdin;
1	PAY-19D5C820898	1	11250000.00	Lunas	2026-04-05 07:18:39	2026-04-05 07:18:39
2	PAY-19C0B0123B0	2	14900000.00	Belum Lunas	2026-01-29 18:25:50	2026-01-29 18:25:50
3	PAY-19C58AB8268	4	775000.00	Belum Lunas	2026-02-13 20:22:41	2026-02-13 20:22:41
4	PAY-19CD02FEA18	5	10950000.00	Lunas	2026-03-09 01:22:07	2026-03-09 01:22:07
5	PAY-19DBD4B83C0	7	15600000.00	Lunas	2026-04-24 02:22:16	2026-04-24 02:22:16
6	PAY-19B9CACB190	8	7875000.00	Belum Lunas	2026-01-08 08:15:22	2026-01-08 08:15:22
7	PAY-19CB148A8D8	10	17700000.00	Belum Lunas	2026-03-03 01:20:55	2026-03-03 01:20:55
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	user-list	web	2026-05-12 04:40:38	2026-05-12 04:40:38
2	user-create	web	2026-05-12 04:40:38	2026-05-12 04:40:38
3	user-edit	web	2026-05-12 04:40:38	2026-05-12 04:40:38
4	user-delete	web	2026-05-12 04:40:38	2026-05-12 04:40:38
5	item-list	web	2026-05-12 04:40:38	2026-05-12 04:40:38
6	item-create	web	2026-05-12 04:40:38	2026-05-12 04:40:38
7	item-edit	web	2026-05-12 04:40:38	2026-05-12 04:40:38
8	item-delete	web	2026-05-12 04:40:38	2026-05-12 04:40:38
9	setting-manage	web	2026-05-12 04:40:38	2026-05-12 04:40:38
10	sale-list	web	2026-05-12 04:40:38	2026-05-12 04:40:38
11	sale-create	web	2026-05-12 04:40:38	2026-05-12 04:40:38
12	sale-edit	web	2026-05-12 04:40:38	2026-05-12 04:40:38
13	sale-delete	web	2026-05-12 04:40:38	2026-05-12 04:40:38
14	payment-list	web	2026-05-12 04:40:38	2026-05-12 04:40:38
15	payment-create	web	2026-05-12 04:40:38	2026-05-12 04:40:38
16	payment-edit	web	2026-05-12 04:40:38	2026-05-12 04:40:38
17	payment-delete	web	2026-05-12 04:40:38	2026-05-12 04:40:38
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
1	1
2	1
3	1
4	1
5	1
6	1
7	1
8	1
9	1
10	1
11	1
12	1
13	1
14	1
15	1
16	1
17	1
5	2
6	2
7	2
8	2
10	2
11	2
12	2
13	2
14	2
15	2
16	2
17	2
10	3
11	3
12	3
14	3
15	3
16	3
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	Admin	web	2026-05-12 04:40:38	2026-05-12 04:40:38
2	Supervisor	web	2026-05-12 04:40:38	2026-05-12 04:40:38
3	Staff	web	2026-05-12 04:40:38	2026-05-12 04:40:38
\.


--
-- Data for Name: sale_items; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.sale_items (id, sale_id, item_id, quantity, price, subtotal, created_at, updated_at) FROM stdin;
1	1	1	2	5000000.00	10000000.00	2026-04-05 07:17:39	2026-04-05 07:17:39
2	1	3	5	150000.00	750000.00	2026-04-05 07:17:39	2026-04-05 07:17:39
3	1	5	4	50000.00	200000.00	2026-04-05 07:17:39	2026-04-05 07:17:39
4	1	4	1	300000.00	300000.00	2026-04-05 07:17:39	2026-04-05 07:17:39
5	2	1	1	5000000.00	5000000.00	2026-01-29 18:24:50	2026-01-29 18:24:50
6	2	3	1	150000.00	150000.00	2026-01-29 18:24:50	2026-01-29 18:24:50
7	2	5	1	50000.00	50000.00	2026-01-29 18:24:50	2026-01-29 18:24:50
8	2	4	2	300000.00	600000.00	2026-01-29 18:24:50	2026-01-29 18:24:50
9	2	2	2	12000000.00	24000000.00	2026-01-29 18:24:50	2026-01-29 18:24:50
10	3	1	4	5000000.00	20000000.00	2026-05-05 04:30:53	2026-05-05 04:30:53
11	3	3	1	150000.00	150000.00	2026-05-05 04:30:53	2026-05-05 04:30:53
12	3	5	5	50000.00	250000.00	2026-05-05 04:30:53	2026-05-05 04:30:53
13	3	4	2	300000.00	600000.00	2026-05-05 04:30:53	2026-05-05 04:30:53
14	4	3	7	150000.00	1050000.00	2026-02-13 20:21:41	2026-02-13 20:21:41
15	4	5	4	50000.00	200000.00	2026-02-13 20:21:41	2026-02-13 20:21:41
16	4	4	1	300000.00	300000.00	2026-02-13 20:21:41	2026-02-13 20:21:41
17	5	1	2	5000000.00	10000000.00	2026-03-09 01:21:07	2026-03-09 01:21:07
18	5	3	3	150000.00	450000.00	2026-03-09 01:21:07	2026-03-09 01:21:07
19	5	5	4	50000.00	200000.00	2026-03-09 01:21:07	2026-03-09 01:21:07
20	5	4	1	300000.00	300000.00	2026-03-09 01:21:07	2026-03-09 01:21:07
21	6	1	10	5000000.00	50000000.00	2026-04-12 14:27:15	2026-04-12 14:27:15
22	6	3	1	150000.00	150000.00	2026-04-12 14:27:15	2026-04-12 14:27:15
23	6	5	2	50000.00	100000.00	2026-04-12 14:27:15	2026-04-12 14:27:15
24	6	4	2	300000.00	600000.00	2026-04-12 14:27:15	2026-04-12 14:27:15
25	7	1	3	5000000.00	15000000.00	2026-04-24 02:21:16	2026-04-24 02:21:16
26	7	3	2	150000.00	300000.00	2026-04-24 02:21:16	2026-04-24 02:21:16
27	7	4	1	300000.00	300000.00	2026-04-24 02:21:16	2026-04-24 02:21:16
28	8	1	3	5000000.00	15000000.00	2026-01-08 08:14:22	2026-01-08 08:14:22
29	8	3	5	150000.00	750000.00	2026-01-08 08:14:22	2026-01-08 08:14:22
30	9	1	8	5000000.00	40000000.00	2026-04-08 02:55:39	2026-04-08 02:55:39
31	9	3	3	150000.00	450000.00	2026-04-08 02:55:39	2026-04-08 02:55:39
32	9	5	1	50000.00	50000.00	2026-04-08 02:55:39	2026-04-08 02:55:39
33	10	1	7	5000000.00	35000000.00	2026-03-03 01:19:55	2026-03-03 01:19:55
34	10	3	2	150000.00	300000.00	2026-03-03 01:19:55	2026-03-03 01:19:55
35	10	5	2	50000.00	100000.00	2026-03-03 01:19:55	2026-03-03 01:19:55
\.


--
-- Data for Name: sales; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.sales (id, code, total_price, payment_status, created_at, updated_at) FROM stdin;
1	SLS-19D5C811E38	11250000.00	Sudah Dibayar	2026-04-05 07:17:39	2026-04-05 07:17:39
2	SLS-19C0B003950	29800000.00	Dibayar Sebagian	2026-01-29 18:24:50	2026-01-29 18:24:50
3	SLS-19DF6673848	21000000.00	Belum Dibayar	2026-05-05 04:30:53	2026-05-05 04:30:53
4	SLS-19C58AA9808	1550000.00	Dibayar Sebagian	2026-02-13 20:21:41	2026-02-13 20:21:41
5	SLS-19CD02EFFB8	10950000.00	Sudah Dibayar	2026-03-09 01:21:07	2026-03-09 01:21:07
6	SLS-19D8216F1B8	50850000.00	Belum Dibayar	2026-04-12 14:27:15	2026-04-12 14:27:15
7	SLS-19DBD4A9960	15600000.00	Sudah Dibayar	2026-04-24 02:21:16	2026-04-24 02:21:16
8	SLS-19B9CABC730	15750000.00	Dibayar Sebagian	2026-01-08 08:14:22	2026-01-08 08:14:22
9	SLS-19D6B0453F8	40500000.00	Belum Dibayar	2026-04-08 02:55:39	2026-04-08 02:55:39
10	SLS-19CB147BE78	35400000.00	Dibayar Sebagian	2026-03-03 01:19:55	2026-03-03 01:19:55
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
sdDTIsJB8af1kc6vUKmQHrOJap2gAnn1jOKtJR8Y	3	172.22.0.1	Mozilla/5.0 (X11; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0	eyJfdG9rZW4iOiJzTGZCZmE4Y3BidmdMQ1VIMk9yMTg1WGR3TzVKdHBZOXU4QzdTdXllIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvZGFzaGJvYXJkIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6M30=	1778561019
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	Admin User	admin@mail.com	\N	$2y$12$iqIL50phDyvQv.m/4x/ZUOJNEY2jdm5PTNw5TELPNPo37.lDvtvI2	\N	2026-05-12 04:40:39	2026-05-12 04:40:39
2	Supervisor User	supervisor@mail.com	\N	$2y$12$onGZTZRHoyz4aIwobOLaeuYKm9pTzeuRyUw1t60Df8Gu6vJluUs4e	\N	2026-05-12 04:40:40	2026-05-12 04:40:40
3	Staff User	staff@mail.com	\N	$2y$12$BZi2EhiqXZeMMyCyI1EDsODRjljl0HVxPzw4KKA5shCz7E5SSS3ai	\N	2026-05-12 04:40:40	2026-05-12 04:40:40
\.


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.categories_id_seq', 3, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.items_id_seq', 5, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.migrations_id_seq', 9, true);


--
-- Name: payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.payments_id_seq', 7, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.permissions_id_seq', 17, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.roles_id_seq', 3, true);


--
-- Name: sale_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.sale_items_id_seq', 35, true);


--
-- Name: sales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.sales_id_seq', 10, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.users_id_seq', 3, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_prefix_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_prefix_unique UNIQUE (prefix);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: items items_code_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.items
    ADD CONSTRAINT items_code_unique UNIQUE (code);


--
-- Name: items items_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.items
    ADD CONSTRAINT items_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payments payments_code_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_code_unique UNIQUE (code);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: payments payments_sale_id_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_sale_id_unique UNIQUE (sale_id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sale_items sale_items_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sale_items
    ADD CONSTRAINT sale_items_pkey PRIMARY KEY (id);


--
-- Name: sales sales_code_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_code_unique UNIQUE (code);


--
-- Name: sales sales_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: items items_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.items
    ADD CONSTRAINT items_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: payments payments_sale_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES public.sales(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: sale_items sale_items_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sale_items
    ADD CONSTRAINT sale_items_item_id_foreign FOREIGN KEY (item_id) REFERENCES public.items(id);


--
-- Name: sale_items sale_items_sale_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sale_items
    ADD CONSTRAINT sale_items_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES public.sales(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict 5ULTiAuIsyv77BWzz4CFgQtEcEb94JgyUtychhIj0cYBZskwWmljX3tHhlGVafT

