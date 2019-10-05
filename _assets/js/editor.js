$(document).ready(function() {
	var wbbOpt = {
		allButtons: {
			quote: {
				transform: {
					'<div class="quote">{SELTEXT}</div>':'[quote]{SELTEXT}[/quote]', 
					'<div class="quote postid-{POSTID}"><cite>{AUTHOR}</cite> wrote:</cite>{SELTEXT}</div>':'[quote={AUTHOR};{POSTID}]{SELTEXT}[/quote]'
				}
			}
		}
	}
});