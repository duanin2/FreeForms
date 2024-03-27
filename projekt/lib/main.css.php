<?php
header("Content-Type: text/css");

$scheme = $_GET["scheme"] ?? "latte";
?>

@import url('https://unpkg.com/@catppuccin/palette/css/catppuccin.css');

* {
	margin: 0;
	padding: 0;

	color: var(--ctp-<?php echo $scheme; ?>-text);
}

body {
	background-color: var(--ctp-<?php echo $scheme; ?>-mantle);
}

header {
	--height: 100px;

	height: var(--height);
	width: 100%;
	line-height: var(--height);

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
}

main {
	width: fit-content;
	min-width: 5vw;
	max-width: 95vw;
	margin: 0 auto;

	padding: 20px;
	border: 2px solid var(--ctp-<?php echo $scheme; ?>-surface2);
	border-radius: 10px;

	height: fit-content;

	position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

	background-color: var(--ctp-<?php echo $scheme; ?>-base);

	& > h1, & > h2, & > h3, & > h4, & > h5, & > h6 {
		margin-bottom: 10px;
		color: var(--ctp-<?php echo $scheme; ?>-subtext0);
	}
	& > h1 {
		color: var(--ctp-<?php echo $scheme; ?>-text);
	}
}

::selection {
	background-color: rgba(var(--ctp-<?php echo $scheme; ?>-surface2), 0.4);
}