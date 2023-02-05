

export enum Ruolo {
    ADMIN = 1,
    GIOCATORE = 2,
    VISITATORE = 3,
}

export enum ComboRuolo {
    ADMIN_DESC = "admin",
    GIOCATORE_DESC = "giocatore",
    VISITATORE_DESC = "visitatore",
    ADMIN_VAL = 1,
    GIOCATORE_VAL = 2,
    VISITATORE_VAL = 3,
}

export enum FasiCompetizione {
    GIRONI = 1,
    SPAREGGI = 2,
    QUARTI = 3,
    SEMI_FINALE = 4,
    FINALE = 5
}

export enum PeriodoGiornata {
    PRE_PARTITA = 1,
    PARTITA_LIVE = 2,
    POST_PARTITA = 3
}