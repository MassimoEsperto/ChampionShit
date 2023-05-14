import language_ita from 'src/assets/language/ita.json';
import language_eng from 'src/assets/language/eng.json';
import language_fra from 'src/assets/language/fra.json';
import language_cin from 'src/assets/language/cin.json';
import { PeriodoGiornata, StatiUtente } from './enums';

export abstract class GlobalComponent {

    language: any = this.getLinguaggio()


    loading_page: boolean = true;
    hidden_page: boolean = true;
    loading_btn: boolean = false;

    resetLoading() {
        this.loading_page = false;
        this.loading_btn = false;
    }

    getLinguaggio() {
        let key = localStorage.getItem("shit-language");
        return key ? this.chengeLinguaggio(key) : language_ita
    }

    setLinguaggio(lan: string) {
        localStorage.setItem('shit-language', lan);
        this.refreshPage();
    }

    chengeLinguaggio(key: string) {

        switch (key) {
            case "ita":
                return language_ita
            case "eng":
                return language_eng
            case "fra":
                return language_fra
            case "cin":
                return language_cin

            default:
                return language_ita
        }
    }

    refreshPage() {
        window.location.reload();
    }

    loadPage(spinner: any) {
        this.loading_page = false;
        setTimeout(() => {
            spinner.clear(), this.hidden_page = false;
        }, 1000);
    }

    startPage(spinner: any) {
        this.loading_page = true;
        spinner.view();
    }

    //enums
    PERIODO_GIORNATA = PeriodoGiornata;
    STATI_UTNTE = StatiUtente;

    //combo
    combobooleano = [{
        descrizione: "SI",
        valore: "1"
    }, {
        descrizione: "NO",
        valore: "0"
    }]

}