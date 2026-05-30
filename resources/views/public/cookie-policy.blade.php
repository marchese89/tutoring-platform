@extends('layouts.layout-bootstrap')

@section('content')
    <div>
        <style>
            ol {
                padding-left: 20px;
            }

            p {
                white-space: wrap;
            }
        </style>
        <table style="width: 70%;margin-left:auto;margin-right:auto" id="pannello_controllo" >
            <tr colspan="3">
                <td align="center">
                    <br />
                    <br />
                    <h2 align="center">Utilizzo dei Cookie</h2>
                    <p>
                        Questo sito utilizza i cookie al fine di garantire agli utenti i
                        propri servizi
                    </p>
                    <p>
                        Per usufruire dei servizi offerti &egrave; obbligatorio acconsentirne
                        l&apos;utilizzo
                    </p>
                    <p>
                        Qualora vengano totalmente bloccati i cookie alcune funzioni del sito
                        saranno inutilizzabili
                    </p>
                    <br />
                    <h3>Cosa sono i cookie:</h3>
                    I cookie sono piccoli file, memorizzati nel computer degli utenti dai
                    server web durante la navigazione,
                    <p>
                        utili a salvare le preferenze e a migliorare le prestazioni dei siti
                        web (in alcuni casi indispensabili al corretto funzionamento degli
                        stessi). <br />
                    </p>

                    <h3>Cookie tecnici</h3>
                    I cookie tecnici sono cookie che il sito utilizza per svolgere le sue
                    funzioni principali.<br />
                    <br />Sul sito sono attualmente utilizzati i seguenti cookie tecnici:<br />
                    <br />
                    <table  class="table" style="white-space: wrap;width:65%" border="1" rules="all">
                        <tr>
                            <td><strong>XSRF-TOKEN:</strong></td>
                            <td> Questo cookie viene utilizzato per proteggere le applicazioni Laravel da
                                attacchi Cross-Site Request Forgery (CSRF). Contiene un token univoco che viene utilizzato
                                per verificare l'autenticità delle richieste.
                            </td>
                        </tr>
                        <tr>
                            <td><strong>laravel_session:</strong></td>
                            <td> Questo cookie viene utilizzato per gestire la sessione dell'utente.
                                Contiene un identificatore univoco per identificare la sessione corrente dell'utente.
                            </td>
                        </tr>
                        <tr>
                            <td><strong>remember_web_{hash}:</strong></td>
                            <td> Questo cookie viene utilizzato per ricordare le credenziali di accesso
                                dell'utente in modo che non debba effettuare nuovamente l'accesso ogni volta che visita il
                                sito. L'{hash} nel nome del cookie rappresenta un identificatore univoco per l'utente.
                            </td>
                        </tr>
                    </table>





                    <h3>Cookie di profilazione</h3>
                    I cookie di profilazione sono utilizzati per creare profili degli
                    utenti, basati sulle preferenze ed i gusti manifestati<br />
                    durante la navigazione su Internet, e far visualizzare messaggi
                    pubblicitari coerenti con i profili.<br />
                    <p>
                        Questo sito non utilizza alcun cookie di profilazione<br />
                        <br />
                    </p>

                    <h3>Come gestire i cookie ed opporti al loro utilizzo</h3>
                    Ci sono diverse opzioni per gestire, disabilitare e cancellare i
                    cookie.<br />
                    <br />
                    <p>
                        (1) Cambia le impostazioni del tuo browser Segui le istruzioni fornite
                        dal produttore del browser che utilizzi per scoprire come gestire,<br />
                        disabilitare o cancellare tutti i cookie (tecnici, analytics e di
                        profilazione):</p>
                    <a href="https://support.microsoft.com/it-it/microsoft-edge/eliminare-i-cookie-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09"
                        target="_blank">Edge</a>
                    <br />
                    <a href="https://support.google.com/chrome/answer/95647?hl=it" target="_blank">Chrome</a>
                    <br />
                    <a href="https://support.mozilla.org/it/kb/Attivare%20e%20disattivare%20i%20cookie"
                        target="_blank">Firefox</a>
                    <br />
                    <a href="https://support.apple.com/kb/PH17191?locale=it_IT" target="_blank">Safari</a>
                    <br />
                    <a href="http://help.opera.com/Windows/10.00/it/cookies.html" target="_blank">Opera</a>
                    <br />
                    <p>
                        (2) Utilizza i gli strumenti interattivi forniti dalle terze parti Per
                        disabilitare i cookie di terze parti, consulta le informative privacy
                        dei terzi che installano cookie analytics
                        o cookie di profilazione per conoscere gli altri strumenti a tua
                        disposizione per gestire, disabilitare e cancellare i cookie,
                        e pi&ugrave; in generale per opporti al loro utilizzo. Ricordati che,
                        disabilitando i cookie di terze parti,
                        (i) ti opponi al loro utilizzo non soltanto sul Sito ma su tutti i siti
                        Internet su cui tali cookie sono utilizzati e
                        (ii) la possibilit&agrave; di navigare sul Sito ed utilizzarne le
                        funzionalit&agrave; non sar&agrave; in alcun modo pregiudicata.
                    </p>
                    <br />
                    <h3>Altre informazioni</h3>
                    Il proprietario del sito agisce in qualità di titolare del trattamento
                    dei dati relativi ai cookie.<br />
                    Nessuna informazione personale verr&agrave; raccolta dai cookie
                    utilizzati.<br />

                    <h3>I diritti degli utenti</h3>
                    <p>
                        Gli utenti che visitano il nostro sito web hanno il diritto di
                        esercitare alcuni diritti riguardo ai dati personali raccolti
                        attraverso i cookie. Di seguito sono elencati i diritti che gli utenti
                        possono esercitare:
                    </p>
                    <p>
                        Accesso ai dati personali: Gli utenti hanno il diritto di ottenere
                        conferma che i dati personali siano o meno trattati e, se lo sono,
                        di accedere ai dettagli specifici di tale trattamento.
                    </p>

                    <p>
                        Retifica dei dati personali: Nel caso in cui gli utenti ritengano
                        che i dati personali raccolti siano inesatti o incompleti, hanno
                        il diritto di richiedere la rettifica o l'integrazione di tali
                        dati.
                    </p>

                    <p>
                        Cancellazione dei dati personali: Gli utenti hanno il diritto di
                        richiedere la cancellazione dei propri dati personali, se non vi è
                        più una ragione legittima per il loro trattamento o se si
                        oppongono al trattamento dei loro dati per scopi di marketing
                        diretto.
                    </p>
                    <p>
                        Limitazione del trattamento: Gli utenti hanno il diritto di
                        richiedere la limitazione del trattamento dei propri dati
                        personali in determinate circostanze, ad esempio se contestano
                        l'accuratezza dei dati o se il trattamento è illecito.
                    </p>

                    <p>
                        Obiezione al trattamento: Gli utenti hanno il diritto di opporsi
                        al trattamento dei propri dati personali per motivi legittimi,
                        compresa l'elaborazione per finalità di marketing diretto.
                    </p>

                    <p>
                        Portabilità dei dati: Gli utenti hanno il diritto di ricevere in
                        un formato strutturato, di uso comune e leggibile da dispositivo
                        automatico, i dati personali che hanno fornito e hanno il diritto
                        di trasmettere tali dati a un altro titolare del trattamento senza
                        impedimenti, se tecnicamente fattibile.
                    </p>

                    <p>
                        Revoca del consenso: Gli utenti hanno il diritto di revocare il
                        consenso prestato per il trattamento dei loro dati personali in
                        qualsiasi momento.
                    </p>
                    <p>
                        Per esercitare questi diritti o per ottenere ulteriori informazioni
                        sul trattamento dei dati personali attraverso i cookie, gli utenti
                        possono contattarci utilizzando le informazioni di contatto fornite
                        nella sezione "Contatti" di questa Cookie Policy.
                    </p>
                    <p>
                        Si prega di notare che, in alcuni casi, potremmo dover conservare
                        determinate informazioni personali degli utenti per adempiere a
                        obblighi legali o per scopi legittimi, come la difesa in caso di
                        controversie.
                    </p>
                </td>
            </tr>
        </table>
    </div>
@endsection
