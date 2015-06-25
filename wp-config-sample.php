<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'wp');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '123456');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'OrVORx8{=)(},b+)i^iu(^lPqF.j-I]IeM62C?|ixJxqfy9;1|6BYTW+tV*kkqD)');
define('SECURE_AUTH_KEY',  ';^%$ _?=-D]|W4g2-+Q8*Y2$G*tMh-I(wWL8EeEe<tY;-Xe`fjia^gFBfne~gt[,');
define('LOGGED_IN_KEY',    '7V[+I:(Gim.%!-GR(w7-Svr$oD*CCUoLo+ZLWvRo2]`{6=U|kp0InS^VKj3$k.mz');
define('NONCE_KEY',        'ZpDQv1j_B;SdHly4iU[j]$Z*jHtYF+za*:PJTGSlx!;//}&*_|nY!|5uXG{:/f^L');
define('AUTH_SALT',        '|YYhsMWD[$Nht~vse1%B=~fRd~=C6qNn#R9B{rV*m5)R;rN7!{S*$TM2IS!_geu~');
define('SECURE_AUTH_SALT', 'Le]f[Q9+$p SQgcMPPBzN)%NpA{9>W4EW}U{+SO`4G+YvzRlYgc6Vhf6l#}]Lw#o');
define('LOGGED_IN_SALT',   '9FM+a+z#$uX 9E=Q8&ppy{,TIGg%8-|*$.@.0kHqVAfY[Y:E4AfN-E]rE*=Yyfb`');
define('NONCE_SALT',       '8UPYuqiZiL|;N^OP|v1;l-8~xkr^TzJy8>S@a~NrbTal- *fkWk}}MU3NgA2n5[I');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';


/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', true);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
