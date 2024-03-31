<?php
header("Content-Type: text/css");

$scheme = $_GET["scheme"] ?? "latte";
?>

@import url('https://unpkg.com/@catppuccin/palette/css/catppuccin.css');

* {
	margin: 0;
	padding: 0;

	color: var(--ctp-<?php echo $scheme; ?>-text);

	& a {
		color: var(--ctp-<?php echo $scheme; ?>-blue);
		text-decoration: none;

		&:hover {
			font-size: 1.05em;
			scale: 105%;
		}
	}
}

body {
	background-color: var(--ctp-<?php echo $scheme; ?>-mantle);
}
input, select, button {
	background-color: var(--ctp-<?php echo $scheme; ?>-surface0);
	border: 1px solid var(--ctp-<?php echo $scheme; ?>-overlay1);
	border-radius: 4px;
	padding: 4px;
}

header {
	--height: 8vh;
	top: 0;
	left: 0;
}
footer {
	--height: 8vh;
	bottom: 0;
	left: 0;
}

header, footer {
	height: var(--height);
	width: 100%;
	line-height: var(--height);

	position: fixed;
	
	background-color: var(--ctp-<?php echo $scheme; ?>-base);

	& > * {
		margin: 0 50px;

		a {
			color: var(--ctp-<?php echo $scheme; ?>-blue);
			text-decoration: none;

			&:hover {
				font-size: 1.05em;
				scale: 105%;
			}
		}
	}
	& > .logo {
		float: left;
		margin-top: calc(var(--height) / 10);
		& img {
			height: calc(var(--height) / 5 * 4);
		}
	}
	& > .copyright img {
		float: right;
		margin-top: calc(var(--height) / 10);
		padding-right: 20px;
		height: calc(var(--height) / 5 * 4);
	}
	& > .buttons {
		float: right;

		& > a {
			margin: 0 10px;

			&:first-of-type {
				margin-left: 0;
			}
			&:last-of-type {
				margin-right: 0;
			}
		}
	}

	& > .copyright {
		float: left;
		text-align: left;
	}
	& > .accessCount {
		float: right;
		text-align: right;
	}
}

main {
	width: fit-content;
	min-width: 5vw;
	max-width: 95vw;

	padding: 20px;
	border: 2px solid var(--ctp-<?php echo $scheme; ?>-overlay0);
	border-radius: 10px;

	height: fit-content;

	position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

	background-color: var(--ctp-<?php echo $scheme; ?>-base);

	& h1 {
		margin-bottom: 20px;
		text-align: center;
		color: var(--ctp-<?php echo $scheme; ?>-text);
	}
	& h2, & h3 {
		margin: 15px 0;
		color: var(--ctp-<?php echo $scheme; ?>-subtext0);
	}
	& h4, & h5, & h6 {
		margin: 10px 0;
		color: var(--ctp-<?php echo $scheme; ?>-subtext1);
	}
	& p {
		margin-bottom: 5px;
		&:last-of-type {
			margin-bottom: 0;
		}
	}

	& form tr {
		& td, & th {
			&:first-of-type { padding-left: 0; }
			&:last-of-type { padding-right: 0; }
		}
		&:first-of-type {
			th, td {
				padding-top: 0;
			}
		}
		&:last-of-type {
			th, td {
				padding-bottom: 0;
			}
		}
	}

	& table {
		th, td {
			padding: 5px;
		}
	}

	& > table {
		border: 1px solid var(--ctp-<?php echo $scheme; ?>-overlay1);
		border-collapse: collapse;

		& th, & td {
			padding: 5px;
			border: 1px solid var(--ctp-<?php echo $scheme; ?>-overlay1);
		}
	}
}

.error {
	color: var(--ctp-<?php echo $scheme; ?>-red);
}

::selection {
	background-color: rgba(var(--ctp-<?php echo $scheme; ?>-surface2), 0.4);
}