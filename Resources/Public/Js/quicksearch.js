(function(a,b,c,d){a.fn.quicksearch=function(c,d){var e,f,g,h,i="",j=this,k=a.extend({delay:100,selector:null,stripeRows:null,loader:null,noResults:"",bind:"keyup",onBefore:function(){return},onAfter:function(){return},show:function(){this.style.display=""},hide:function(){this.style.display="none"},prepareQuery:function(a){return a.toLowerCase().split(" ")},testQuery:function(a,b,c){for(var d=0;d<a.length;d+=1){if(b.indexOf(a[d])===-1){return false}}return true}},d);this.go=function(){var a=0,b=true,c=k.prepareQuery(i),d=i.replace(" ","").length===0;for(var a=0,e=g.length;a<e;a++){if(d||k.testQuery(c,f[a],g[a])){k.show.apply(g[a]);b=false}else{k.hide.apply(g[a])}}if(b){this.results(false)}else{this.results(true);this.stripe()}this.loader(false);k.onAfter();return this};this.stripe=function(){if(typeof k.stripeRows==="object"&&k.stripeRows!==null){var b=k.stripeRows.join(" ");var c=k.stripeRows.length;h.not(":hidden").each(function(d){a(this).removeClass(b).addClass(k.stripeRows[d%c])})}return this};this.strip_html=function(b){var c=b.replace(new RegExp("<[^<]+>","g"),"");c=a.trim(c.toLowerCase());return c};this.results=function(b){if(typeof k.noResults==="string"&&k.noResults!==""){if(b){a(k.noResults).hide()}else{a(k.noResults).show()}}return this};this.loader=function(b){if(typeof k.loader==="string"&&k.loader!==""){b?a(k.loader).show():a(k.loader).hide()}return this};this.cache=function(){h=a(c);if(typeof k.noResults==="string"&&k.noResults!==""){h=h.not(k.noResults)}var b=typeof k.selector==="string"?h.find(k.selector):a(c).not(k.noResults);f=b.map(function(){return j.strip_html(this.innerHTML)});g=h.map(function(){return this});return this.go()};this.trigger=function(){this.loader(true);k.onBefore();b.clearTimeout(e);e=b.setTimeout(function(){j.go()},k.delay);return this};this.cache();this.results(true);this.stripe();this.loader(false);return this.each(function(){a(this).bind(k.bind,function(){i=a(this).val();j.trigger()})})}})(jQuery,this,document)
$('input#jpfaqSearch').quicksearch('.tx-jpfaq-pi1 li');