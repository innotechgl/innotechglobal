var calendar_class = new Class({
    Implements: Options,
    options: {
        link: '',
        meseci: Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"),
        daniUNedelji: ["П", 'У', 'С', 'Ч', 'П', 'С', 'Н'],
        div: '',
        mesec: '',
        godina: '',
        maxProslost: '',
        maxBuducnost: ''
    },
    initialize: function () {

        // Ucitavamo parametre
        this.mesec = options.mesec;
        this.godina = options.godina;
        this.maxProslost = options.maxProslost;
        this.maxBuducnost = options.maxBuducnost;
        this.meseci = options.meseci;
        this.selektovano = new Hash.Cookie('selektovanDatum', {duration: 3600});
        if (this.selektovano.get('mesec') == null || this.selektovano.get('mesec') == '') {
            this.selektovano.extend({
                'mesec': this.mesec,
                'godina': this.godina
            });
        }
        else {
            this.mesec = this.selektovano.get('mesec');
            this.godina = this.selektovano.get('godina');
        }
        // Dani u nedelji
        this.div = options.div;
        // Kreiramo element sa datumima
        this.divDatumi = new Element('div', {
            'id': 'datumi', 'styles': {
                'position': 'relative',
                'float': 'left'
            }
        });
        this.divDatumi.inject(this.div);
        // Dani u nedelji
        //this.dani = ['П','У', 'С', 'Ч', 'П', 'С', 'Н'];
        this.dani = options.daniUNedelji;
        // Generisanje
        this.generisi(mesec, godina);
        this.sliderMesecDatum();
    },
    generisi: function (mesec, godina) {
        // Praznimo container za datume
        $(this.divDatumi).empty();
        if (mesec !== '' && godina !== '' || godina !== null && mesec !== null) {
            this.dan = new Date(this.meseci[mesec - 1] + " 1, " + godina);
        }
        else {
            this.dan = new Date();
        }
        this.i = 0;
        // Prvo ispisujemo nazive dana
        this.dani.each(function (item) {
            this.elTh = new Element('a', {
                'id': 'thDan',
                'class': 'th',
                'text': this.dani[this.i],
                'styles': {
                    'float': 'left',
                    'display': 'block',
                    'text-align': 'center'
                }
            }, this);
            this.elTh.inject(this.divDatumi);
            this.i++;
        }, this);
        // Odredjujemo broj dana razlike u prethodnom mesecu
        this.prethodniMesec = this.mesec - 2;
        this.naredniMesec = this.mesec;
        this.prethodnaGodina = this.godina;
        this.narednaGodina = this.godina;
        // Proveravamo da li je prethodni mesec manji od Januara
        if (this.prethodniMesec < 0) {
            prethodniMesec = 11;
            --this.prethodnaGodina;
        }
        // Proveravamo da li je naredni mesec veci od Decembra
        if (this.naredniMesec > 11) {
            this.naredniMesec = 0;
            this.narednaGodina++;
        }
        // Proveravamo da li dan pocinje sa nedeljom ili ponedeljkom
        this.trenutniDan = this.dan.getDay() - 1;
        // Uzimamo zadnji dan u mesecu
        this.zadnjiDan = new Date(this.meseci[mesec - 1] + " " + this.brojDana(this.mesec - 1, this.godina) + ",  " + this.godina).getDay() - 1;
        console.log('Trenutni dan:' + this.trenutniDan);
        //
        if (this.trenutniDan < 0) {
            this.trenutniDan = 6;
        }
        // Uzimamo broj dana prethodnog meseca
        this.prethodniMesecBrojDana = this.brojDana(this.prethodniMesec, this.prethodnaGodina);
        console.log('Prethodni mesec(Broj dana):' + this.prethodniMesecBrojDana);
        // Kreiramo elemente
        for (this.i = this.prethodniMesecBrojDana - (this.trenutniDan - 1); this.i <= this.prethodniMesecBrojDana; this.i++) {
            this.elTd = new Element('a', {
                'id': 'tdDan',
                'class': 'tdNeaktivni',
                'text': this.i,
                'styles': {
                    'float': 'left',
                    'display': 'block',
                    'text-align': 'center'
                }
            }, this);
            this.elTd.inject(this.divDatumi);
        }
        // Ispisujemo datume
        for (this.i = 1; this.i <= this.brojDana(this.mesec - 1, this.godina); this.i++) {
            this.elTd = new Element('a', {
                'id': 'tdDan',
                'class': 'tdAktivni',
                'text': this.i,
                'styles': {
                    'float': 'left',
                    'display': 'block',
                    'text-align': 'center'
                }
            }, this);
            this.elTd.inject(this.divDatumi);
        }
        // Ispisujemo dane narednog meseca
        if (this.zadnjiDan < 6) {
            this.pocetak = 6 - this.zadnjiDan;
            for (this.i = 1; this.i <= this.pocetak; this.i++) {
                this.elTd = new Element('a', {
                    'id': 'tdDan',
                    'class': 'tdNeaktivni',
                    'text': this.i,
                    'styles': {
                        'float': 'left',
                        'display': 'block',
                        'text-align': 'center'
                    }
                }, this);
                this.elTd.inject(this.divDatumi);
            }
        }
    },
    brojDana: function daysInMonth(iMonth, iYear) {
        return 32 - new Date(iYear, iMonth, 32).getDate();
    },
    sliderMesecDatum: function () {

        // Kreiramo element slider
        this.slider = new Element('div', {'id': 'slider'});
        this.slider.inject($('datumi'), 'after');
        // Ubacujemo element slider Container, content
        this.sliderMesecCont = new Element('div', {
            'id': 'sliderMesecCont',
            'html': '<a id="goreMesec">&nbsp;</a><a id="doleMesec">&nbsp;</a>'
        });
        // Pravimo container za gore-dole i mesec container
        this.sliderGodinaCont = new Element('div', {
            'id': 'sliderGodinaCont',
            'html': '<a id="goreGodina">&nbsp;</a><a id="doleGodina">&nbsp;</a>'
        });
        // Pravimo container za gore-dole i godina container
        this.sliderMesecCont.inject($(this.slider));
        this.sliderGodinaCont.inject($(this.sliderMesecCont), 'after');
        // Mesec
        this.sliderMesecContainer = new Element('div', {'id': 'sliderMesecContainer', 'class': 'sliderMesecContainer'});
        this.sliderMesecContent = new Element('div', {'id': 'sliderMesecContent', 'class': 'sliderMesecContent'});
        // Godina
        this.sliderGodinaContainer = new Element('div', {
            'id': 'sliderGodinaContainer',
            'class': 'sliderGodinaContainer'
        });
        this.sliderGodinaContent = new Element('div', {'id': 'sliderGodinaContent', 'class': 'sliderGodinaContent'});
        // Ubacujemo slider cont i content mesec
        this.sliderMesecContainer.inject($('goreMesec'), 'after');
        this.sliderMesecContent.inject($(this.sliderMesecContainer));
        // Ubacujemo slider cont i content godina
        this.sliderGodinaContainer.inject($('goreGodina'), 'after');
        this.sliderGodinaContent.inject($(this.sliderGodinaContainer));
        // Uzimamo visinu datuma kalendara
        this.visinaDatuma = $('datumi').getSize().y;
        // Odredjujemo visinu containera selektora
        this.elementVisina = Math.ceil(this.visinaDatuma / 6);
        this.visinaContainerSelektor = this.elementVisina * 6;
        this.visinaContent = this.visinaContainerSelektor * 2;
        // Kreiramo mesece
        for (this.i = 1; this.i <= 12; this.i++) {
            this.el = new Element('a', {
                'class': 'sliderIzbor',
                'text': this.i,
                'styles': {
                    'display': 'block',
                    'text-align': 'center'
                }
            }, this);
            // Insertujemo mesece u slider
            this.el.inject(this.sliderMesecContent);
        }
        // Kreiramo godine
        for (this.i = this.maxProslost; this.i <= this.maxBuducnost; this.i++) {
            this.elGod = new Element('a', {
                'class': 'sliderIzbor',
                'text': this.i,
                'styles': {
                    'display': 'block',
                    'text-align': 'center'
                }
            }, this);
            // Insertujemo mesece u slider
            this.elGod.inject(this.sliderGodinaContent);
        }
        // Definisemo stranicu za mesec i godinu
        this.stranicaMesec = 0;
        this.brStranicaMesec = Math.ceil($(this.sliderMesecContainer).getSize().y / $(this.sliderMesecContent).getSize().y);
        this.slideMesecVisina = $(this.sliderMesecContainer).getSize().y
        this.stranicaGodina = 0;
        this.brStranicaGodina = Math.ceil($(this.sliderGodinaContainer).getSize().y / $(this.sliderGodinaContent).getSize().y);
        this.slideGodinaVisina = $(this.sliderGodinaContainer).getSize().y
        // Obelezavamo selektovane slidere
        $(this.sliderMesecContent).getChildren().each(function (item) {
            if ($(item).get('text') == this.mesec) {
                $(item).addClass('selektovan');
                this.mesecSelektovan = $(item);
            }
        }, this);
        $(this.sliderGodinaContent).getChildren().each(function (item) {
            if ($(item).get('text') == this.godina) {
                $(item).addClass('selektovan');
                this.godinaSelektovan = $(item);
            }
        }, this);
        // Startujemo events
        this.events();
    },
    pripremaPromeneSlidera: function (slider, stranica) {
        switch (slider) {
            case 'mesec':
                this.stranicaMesec = stranica;
                if (this.stranicaMesec < 0) {
                    this.stranicaMesec = 0;
                }
                if (this.stranicaMesec > this.brStranicaMesec) {
                    this.stranicaMesec = this.brStranicaMesec;
                }
                this.pomeriStranicu(slider, this.stranicaMesec);
                break;
            case 'godina':
                this.stranicaGodina = stranica;
                if (this.stranicaGodina < 0) {
                    this.stranicaGodina = 0;
                }
                if (this.stranicaGodina > this.brStranicaGodina) {
                    this.stranicaGodina = this.brStranicaGodina;
                }
                this.pomeriStranicu(slider, this.stranicaGodina);
                break;
        }
    },
    pomeriStranicu: function (slider, stranica) {
        switch (slider) {
            case "mesec":
                this.top = -( stranica * this.slideMesecVisina );
                //		$('contFoto').fade(0);
                var fx = new Fx.Morph(this.sliderMesecContent, {
                    duration: 'long',
                    transition: Fx.Transitions.Sine.easeOut
                });
                fx.start({
                    'top': this.top
                });
                break;
            case "godina":
                this.top = -( stranica * this.slideGodinaVisina );
                //		$('contFoto').fade(0);
                var fx = new Fx.Morph(this.sliderGodinaContent, {
                    duration: 'long',
                    transition: Fx.Transitions.Sine.easeOut
                });
                fx.start({
                    'top': this.top
                });
                break;
        }
    },
    obeleziElement: function (tip, el) {
        switch (tip) {
            case "mesec":
                // Skidamo obelezje sa prethodno selektovanog meseca
                $(this.mesecSelektovan).removeClass('selektovan');
                // Obelezavamo novoizabrani element
                this.mesecSelektovan = el;
                $(this.mesecSelektovan).addClass('selektovan');
                this.mesec = $(this.mesecSelektovan).get('text');
                this.selektovano.set('mesec', this.mesec);
                break;
            case "godina":
                // Skidamo obelezje sa prethodno selektovane godine
                $(this.godinaSelektovan).removeClass('selektovan');
                // Obelezavamo novoizabrani element
                this.godinaSelektovan = el;
                $(this.godinaSelektovan).addClass('selektovan');
                this.godina = $(this.godinaSelektovan).get('text');
                this.selektovano.set('godina', this.godina);
                break;
        }
        this.generisi(this.mesec, this.godina);
    },
    events: function () {

        // Events za datume
        $(this.datumi).addEvent('click', function (event) {
            this.target = $(event.target);
            if ($(this.target).tagName == 'A') {
            }
        });
        // Event za slidere
        $(this.slider).addEvent('click', function (event) {
            this.target = $(event.target);
            if ($(this.target).tagName == 'A') {
                switch ($(this.target).get('id')) {
                    case "goreMesec":
                        this.stranicaMesec++;
                        this.pripremaPromeneSlidera('mesec', this.stranicaMesec);
                        break;
                    case "doleMesec":
                        --this.stranicaMesec;
                        this.pripremaPromeneSlidera('mesec', this.stranicaMesec);
                        break;
                    case "goreGodina":
                        this.stranicaGodina++;
                        this.pripremaPromeneSlidera('godina', this.stranicaGodina);
                        break;
                    case "doleGodina":
                        --this.stranicaGodina;
                        this.pripremaPromeneSlidera('godina', this.stranicaGodina);
                        break;
                    default:
                        switch ($(this.target).get('text').length) {
                            case 1:
                                this.obeleziElement('mesec', this.target);
                                break;
                            case 2:
                                this.obeleziElement('mesec', this.target);
                                break;
                            case 4:
                                this.obeleziElement('godina', this.target);
                                break;
                        }
                        break;
                }
            }
        }.bind(this));
    }
});