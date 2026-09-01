import { parsePhoneNumberFromString } from "https://cdn.jsdelivr.net/npm/libphonenumber-js@1.12.12/+esm";

const formTools = {

    countriesInformation: new Map([
        [
            "AF",
            { name: "Afganistán", nationality: "Afgano/a", phoneCode: "+93" }
        ],
        [
            "ZA",
            { name: "Sudáfrica", nationality: "Sudafricano/a", phoneCode: "+27" }
        ],
        [
            "AL",
            { name: "Albania", nationality: "Albanés/a", phoneCode: "+355" }
        ],
        [
            "DE",
            { name: "Alemania", nationality: "Alemán/a", phoneCode: "+49" }
        ],
        [
            "AD",
            { name: "Andorra", nationality: "Andorrano/a", phoneCode: "+376" }
        ],
        [
            "AO",
            { name: "Angola", nationality: "Angoleño/a", phoneCode: "+244" }
        ],
        [
            "AG",
            { name: "Antigua y Barbuda", nationality: "Antiguano/a", phoneCode: "+1268" }
        ],
        [
            "SA",
            { name: "Arabia Saudita", nationality: "Saudí", phoneCode: "+966" }
        ],
        [
            "DZ",
            { name: "Argelia", nationality: "Argelino/a", phoneCode: "+213" }
        ],
        [
            "AR",
            { name: "Argentina", nationality: "Argentino/a", phoneCode: "+54" }
        ],
        [
            "AM",
            { name: "Armenia", nationality: "Armenio/a", phoneCode: "+374" }
        ],
        [
            "AU",
            { name: "Australia", nationality: "Australiano/a", phoneCode: "+61" }
        ],
        [
            "AT",
            { name: "Austria", nationality: "Austríaco/a", phoneCode: "+43" }
        ],
        [
            "AZ",
            { name: "Azerbaiyán", nationality: "Azerbaiyano/a", phoneCode: "+994" }
        ],
        [
            "BS",
            { name: "Bahamas", nationality: "Bahameño/a", phoneCode: "+1242" }
        ],
        [
            "BH",
            { name: "Baréin", nationality: "Bareiní", phoneCode: "+973" }
        ],
        [
            "BD",
            { name: "Bangladés", nationality: "Bangladesí", phoneCode: "+880" }
        ],
        [
            "BB",
            { name: "Barbados", nationality: "Barbadense", phoneCode: "+1246" }
        ],
        [
            "BE",
            { name: "Bélgica", nationality: "Belga", phoneCode: "+32" }
        ],
        [
            "BZ",
            { name: "Belice", nationality: "Beliceño/a", phoneCode: "+501" }
        ],
        [
            "BJ",
            { name: "Benín", nationality: "Beninés/a", phoneCode: "+229" }
        ],
        [
            "BY",
            { name: "Bielorrusia", nationality: "Bielorruso/a", phoneCode: "+375" }
        ],
        [
            "BO",
            { name: "Bolivia", nationality: "Boliviano/a", phoneCode: "+591" }
        ],
        [
            "BA",
            { name: "Bosnia y Herzegovina", nationality: "Bosnio/a", phoneCode: "+387" }
        ],
        [
            "BW",
            { name: "Botsuana", nationality: "Botsuano/a", phoneCode: "+267" }
        ],
        [
            "BR",
            { name: "Brasil", nationality: "Brasileño/a", phoneCode: "+55" }
        ],
        [
            "BN",
            { name: "Brunéi", nationality: "Bruneano/a", phoneCode: "+673" }
        ],
        [
            "BG",
            { name: "Bulgaria", nationality: "Búlgaro/a", phoneCode: "+359" }
        ],
        [
            "BF",
            { name: "Burkina Faso", nationality: "Burkinés/a", phoneCode: "+226" }
        ],
        [
            "BI",
            { name: "Burundi", nationality: "Burundés/a", phoneCode: "+257" }
        ],
        [
            "BT",
            { name: "Bután", nationality: "Butanés/a", phoneCode: "+975" }
        ],
        [
            "CV",
            { name: "Cabo Verde", nationality: "Caboverdiano/a", phoneCode: "+238" }
        ],
        [
            "KH",
            { name: "Camboya", nationality: "Camboyano/a", phoneCode: "+855" }
        ],
        [
            "CM",
            { name: "Camerún", nationality: "Camerunés/a", phoneCode: "+237" }
        ],
        [
            "CA",
            { name: "Canadá", nationality: "Canadiense", phoneCode: "+1" }
        ],
        [
            "QA",
            { name: "Catar", nationality: "Catarí", phoneCode: "+974" }
        ],
        [
            "TD",
            { name: "Chad", nationality: "Chadiano/a", phoneCode: "+235" }
        ],
        [
            "CL",
            { name: "Chile", nationality: "Chileno/a", phoneCode: "+56" }
        ],
        [
            "CN",
            { name: "China", nationality: "Chino/a", phoneCode: "+86" }
        ],
        [
            "CY",
            { name: "Chipre", nationality: "Chipriota", phoneCode: "+357" }
        ],
        [
            "CZ",
            { name: "Chequia", nationality: "Checo/a", phoneCode: "+420" }
        ],
        [
            "DK",
            { name: "Dinamarca", nationality: "Danés/a", phoneCode: "+45" }
        ],
        [
            "DJ",
            { name: "Yibuti", nationality: "Yibutiano/a", phoneCode: "+253" }
        ],
        [
            "DM",
            { name: "Dominica", nationality: "Dominiqués/a", phoneCode: "+1767" }
        ],
        [
            "DO",
            { name: "República Dominicana", nationality: "Dominicano/a", phoneCode: "+1809" }
        ],
        [
            "EC",
            { name: "Ecuador", nationality: "Ecuatoriano/a", phoneCode: "+593" }
        ],
        [
            "EG",
            { name: "Egipto", nationality: "Egipcio/a", phoneCode: "+20" }
        ],
        [
            "SV",
            { name: "El Salvador", nationality: "Salvadoreño/a", phoneCode: "+503" }
        ],
        [
            "GQ",
            { name: "Guinea Ecuatorial", nationality: "Ecuatoguineano/a", phoneCode: "+240" }
        ],
        [
            "ER",
            { name: "Eritrea", nationality: "Eritreo/a", phoneCode: "+291" }
        ],
        [
            "EE",
            { name: "Estonia", nationality: "Estonio/a", phoneCode: "+372" }
        ],
        [
            "SZ",
            { name: "Esuatini", nationality: "Suazi", phoneCode: "+268" }
        ],
        [
            "ET",
            { name: "Etiopía", nationality: "Etíope", phoneCode: "+251" }
        ],
        [
            "FJ",
            { name: "Fiyi", nationality: "Fiyiano/a", phoneCode: "+679" }
        ],
        [
            "FI",
            { name: "Finlandia", nationality: "Finlandés/a", phoneCode: "+358" }
        ],
        [
            "FR",
            { name: "Francia", nationality: "Francés/a", phoneCode: "+33" }
        ],
        [
            "GA",
            { name: "Gabón", nationality: "Gabonés/a", phoneCode: "+241" }
        ],
        [
            "GM",
            { name: "Gambia", nationality: "Gambiano/a", phoneCode: "+220" }
        ],
        [
            "GE",
            { name: "Georgia", nationality: "Georgiano/a", phoneCode: "+995" }
        ],
        [
            "GH",
            { name: "Ghana", nationality: "Ghanés/a", phoneCode: "+233" }
        ],
        [
            "GR",
            { name: "Grecia", nationality: "Griego/a", phoneCode: "+30" }
        ],
        [
            "GD",
            { name: "Granada", nationality: "Granadino/a", phoneCode: "+1473" }
        ],
        [
            "GT",
            { name: "Guatemala", nationality: "Guatemalteco/a", phoneCode: "+502" }
        ],
        [
            "GN",
            { name: "Guinea", nationality: "Guineano/a", phoneCode: "+224" }
        ],
        [
            "GW",
            { name: "Guinea-Bisáu", nationality: "Bisauguineano/a", phoneCode: "+245" }
        ],
        [
            "GY",
            { name: "Guyana", nationality: "Guyanés/a", phoneCode: "+592" }
        ],
        [
            "HT",
            { name: "Haití", nationality: "Haitiano/a", phoneCode: "+509" }
        ],
        [
            "HN",
            { name: "Honduras", nationality: "Hondureño/a", phoneCode: "+504" }
        ],
        [
            "HU",
            { name: "Hungría", nationality: "Húngaro/a", phoneCode: "+36" }
        ],
        [
            "IS",
            { name: "Islandia", nationality: "Islandés/a", phoneCode: "+354" }
        ],
        [
            "IN",
            { name: "India", nationality: "Indio/a", phoneCode: "+91" }
        ],
        [
            "ID",
            { name: "Indonesia", nationality: "Indonesio/a", phoneCode: "+62" }
        ],
        [
            "IR",
            { name: "Irán", nationality: "Iraní", phoneCode: "+98" }
        ],
        [
            "IQ",
            { name: "Irak", nationality: "Iraquí", phoneCode: "+964" }
        ],
        [
            "IE",
            { name: "Irlanda", nationality: "Irlandés/a", phoneCode: "+353" }
        ],
        [
            "IL",
            { name: "Israel", nationality: "Israelí", phoneCode: "+972" }
        ],
        [
            "IT",
            { name: "Italia", nationality: "Italiano/a", phoneCode: "+39" }
        ],
        [
            "JM",
            { name: "Jamaica", nationality: "Jamaicano/a", phoneCode: "+1876" }
        ],
        [
            "JP",
            { name: "Japón", nationality: "Japonés/a", phoneCode: "+81" }
        ],
        [
            "JO",
            { name: "Jordania", nationality: "Jordano/a", phoneCode: "+962" }
        ],
        [
            "KZ",
            { name: "Kazajistán", nationality: "Kazajo/a", phoneCode: "+7" }
        ],
        [
            "KE",
            { name: "Kenia", nationality: "Keniano/a", phoneCode: "+254" }
        ],
        [
            "KI",
            { name: "Kiribati", nationality: "Kiribatiano/a", phoneCode: "+686" }
        ],
        [
            "KW",
            { name: "Kuwait", nationality: "Kuwaití", phoneCode: "+965" }
        ],
        [
            "KG",
            { name: "Kirguistán", nationality: "Kirguís", phoneCode: "+996" }
        ],
        [
            "LA",
            { name: "Laos", nationality: "Laosiano/a", phoneCode: "+856" }
        ],
        [
            "LV",
            { name: "Letonia", nationality: "Letón/a", phoneCode: "+371" }
        ],
        [
            "LB",
            { name: "Líbano", nationality: "Libanés/a", phoneCode: "+961" }
        ],
        [
            "LS",
            { name: "Lesoto", nationality: "Lesotense", phoneCode: "+266" }
        ],
        [
            "LR",
            { name: "Liberia", nationality: "Liberiano/a", phoneCode: "+231" }
        ],
        [
            "LY",
            { name: "Libia", nationality: "Libio/a", phoneCode: "+218" }
        ],
        [
            "LI",
            { name: "Liechtenstein", nationality: "Liechtensteiniano/a", phoneCode: "+423" }
        ],
        [
            "LT",
            { name: "Lituania", nationality: "Lituano/a", phoneCode: "+370" }
        ],
        [
            "LU",
            { name: "Luxemburgo", nationality: "Luxemburgués/a", phoneCode: "+352" }
        ],
        [
            "MG",
            { name: "Madagascar", nationality: "Malgache", phoneCode: "+261" }
        ],
        [
            "MW",
            { name: "Malaui", nationality: "Malauí", phoneCode: "+265" }
        ],
        [
            "MY",
            { name: "Malasia", nationality: "Malasio/a", phoneCode: "+60" }
        ],
        [
            "MV",
            { name: "Maldivas", nationality: "Maldivo/a", phoneCode: "+960" }
        ],
        [
            "ML",
            { name: "Malí", nationality: "Maliense", phoneCode: "+223" }
        ],
        [
            "MT",
            { name: "Malta", nationality: "Maltés/a", phoneCode: "+356" }
        ],
        [
            "MA",
            { name: "Marruecos", nationality: "Marroquí", phoneCode: "+212" }
        ],
        [
            "MH",
            { name: "Islas Marshall", nationality: "Marshalés/a", phoneCode: "+692" }
        ],
        [
            "MR",
            { name: "Mauritania", nationality: "Mauritano/a", phoneCode: "+222" }
        ],
        [
            "MU",
            { name: "Mauricio", nationality: "Mauriciano/a", phoneCode: "+230" }
        ],
        [
            "MX",
            { name: "México", nationality: "Mexicano/a", phoneCode: "+52" }
        ],
        [
            "FM",
            { name: "Micronesia", nationality: "Micronesio/a", phoneCode: "+691" }
        ],
        [
            "MD",
            { name: "Moldavia", nationality: "Moldavo/a", phoneCode: "+373" }
        ],
        [
            "MC",
            { name: "Mónaco", nationality: "Monegasco/a", phoneCode: "+377" }
        ],
        [
            "MN",
            { name: "Mongolia", nationality: "Mongol", phoneCode: "+976" }
        ],
        [
            "ME",
            { name: "Montenegro", nationality: "Montenegrino/a", phoneCode: "+382" }
        ],
        [
            "MZ",
            { name: "Mozambique", nationality: "Mozambiqueño/a", phoneCode: "+258" }
        ],
        [
            "MM",
            { name: "Myanmar", nationality: "Birmano/a", phoneCode: "+95" }
        ],
        [
            "NA",
            { name: "Namibia", nationality: "Namibio/a", phoneCode: "+264" }
        ],
        [
            "NR",
            { name: "Nauru", nationality: "Nauruano/a", phoneCode: "+674" }
        ],
        [
            "NP",
            { name: "Nepal", nationality: "Nepalí", phoneCode: "+977" }
        ],
        [
            "NL",
            { name: "Países Bajos", nationality: "Neerlandés/a", phoneCode: "+31" }
        ],
        [
            "NZ",
            { name: "Nueva Zelanda", nationality: "Neozelandés/a", phoneCode: "+64" }
        ],
        [
            "NI",
            { name: "Nicaragua", nationality: "Nicaragüense", phoneCode: "+505" }
        ],
        [
            "NE",
            { name: "Níger", nationality: "Nigerino/a", phoneCode: "+227" }
        ],
        [
            "NG",
            { name: "Nigeria", nationality: "Nigeriano/a", phoneCode: "+234" }
        ],
        [
            "KP",
            { name: "Corea del Norte", nationality: "Norcoreano/a", phoneCode: "+850" }
        ],
        [
            "MK",
            { name: "Macedonia del Norte", nationality: "Macedonio/a", phoneCode: "+389" }
        ],
        [
            "NO",
            { name: "Noruega", nationality: "Noruego/a", phoneCode: "+47" }
        ],
        [
            "OM",
            { name: "Omán", nationality: "Omaní", phoneCode: "+968" }
        ],
        [
            "PK",
            { name: "Pakistán", nationality: "Pakistaní", phoneCode: "+92" }
        ],
        [
            "PW",
            { name: "Palaos", nationality: "Palaoano/a", phoneCode: "+680" }
        ],
        [
            "PA",
            { name: "Panamá", nationality: "Panameño/a", phoneCode: "+507" }
        ],
        [
            "PG",
            { name: "Papúa Nueva Guinea", nationality: "Papú", phoneCode: "+675" }
        ],
        [
            "PY",
            { name: "Paraguay", nationality: "Paraguayo/a", phoneCode: "+595" }
        ],
        [
            "PE",
            { name: "Perú", nationality: "Peruano/a", phoneCode: "+51" }
        ],
        [
            "PH",
            { name: "Filipinas", nationality: "Filipino/a", phoneCode: "+63" }
        ],
        [
            "PL",
            { name: "Polonia", nationality: "Polaco/a", phoneCode: "+48" }
        ],
        [
            "PT",
            { name: "Portugal", nationality: "Portugués/a", phoneCode: "+351" }
        ],
        [
            "GB",
            { name: "Reino Unido", nationality: "Británico/a", phoneCode: "+44" }
        ],
        [
            "CF",
            { name: "República Centroafricana", nationality: "Centroafricano/a", phoneCode: "+236" }
        ],
        [
            "CG",
            { name: "República del Congo", nationality: "Congoleño/a", phoneCode: "+242" }
        ],
        [
            "CD",
            { name: "República Democrática del Congo", nationality: "Congoleño/a", phoneCode: "+243" }
        ],
        [
            "KR",
            { name: "Corea del Sur", nationality: "Surcoreano/a", phoneCode: "+82" }
        ],
        [
            "RO",
            { name: "Rumania", nationality: "Rumano/a", phoneCode: "+40" }
        ],
        [
            "RU",
            { name: "Rusia", nationality: "Ruso/a", phoneCode: "+7" }
        ],
        [
            "RW",
            { name: "Ruanda", nationality: "Ruandés/a", phoneCode: "+250" }
        ],
        [
            "WS",
            { name: "Samoa", nationality: "Samoano/a", phoneCode: "+685" }
        ],
        [
            "SM",
            { name: "San Marino", nationality: "Sanmarinense", phoneCode: "+378" }
        ],
        [
            "ST",
            { name: "Santo Tomé y Príncipe", nationality: "Santomense", phoneCode: "+239" }
        ],
        [
            "SN",
            { name: "Senegal", nationality: "Senegalés/a", phoneCode: "+221" }
        ],
        [
            "RS",
            { name: "Serbia", nationality: "Serbio/a", phoneCode: "+381" }
        ],
        [
            "SC",
            { name: "Seychelles", nationality: "Seychellense", phoneCode: "+248" }
        ],
        [
            "SL",
            { name: "Sierra Leona", nationality: "Sierraleonés/a", phoneCode: "+232" }
        ],
        [
            "SG",
            { name: "Singapur", nationality: "Singapurense", phoneCode: "+65" }
        ],
        [
            "SY",
            { name: "Siria", nationality: "Sirio/a", phoneCode: "+963" }
        ],
        [
            "SO",
            { name: "Somalia", nationality: "Somalí", phoneCode: "+252" }
        ],
        [
            "LK",
            { name: "Sri Lanka", nationality: "Ceilanés/a", phoneCode: "+94" }
        ],
        [
            "SD",
            { name: "Sudán", nationality: "Sudanés/a", phoneCode: "+249" }
        ],
        [
            "SS",
            { name: "Sudán del Sur", nationality: "Sudsudanés/a", phoneCode: "+211" }
        ],
        [
            "SE",
            { name: "Suecia", nationality: "Sueco/a", phoneCode: "+46" }
        ],
        [
            "CH",
            { name: "Suiza", nationality: "Suizo/a", phoneCode: "+41" }
        ],
        [
            "SR",
            { name: "Surinam", nationality: "Surinamés/a", phoneCode: "+597" }
        ],
        [
            "TJ",
            { name: "Tayikistán", nationality: "Tayiko/a", phoneCode: "+992" }
        ],
        [
            "TZ",
            { name: "Tanzania", nationality: "Tanzano/a", phoneCode: "+255" }
        ],
        [
            "TH",
            { name: "Tailandia", nationality: "Tailandés/a", phoneCode: "+66" }
        ],
        [
            "TL",
            { name: "Timor Oriental", nationality: "Timorense", phoneCode: "+670" }
        ],
        [
            "TG",
            { name: "Togo", nationality: "Togolés/a", phoneCode: "+228" }
        ],
        [
            "TO",
            { name: "Tonga", nationality: "Tongano/a", phoneCode: "+676" }
        ],
        [
            "TT",
            { name: "Trinidad y Tobago", nationality: "Trinitense", phoneCode: "+1868" }
        ],
        [
            "TN",
            { name: "Túnez", nationality: "Tunecino/a", phoneCode: "+216" }
        ],
        [
            "TR",
            { name: "Turquía", nationality: "Turco/a", phoneCode: "+90" }
        ],
        [
            "TM",
            { name: "Turkmenistán", nationality: "Turcomano/a", phoneCode: "+993" }
        ],
        [
            "TV",
            { name: "Tuvalu", nationality: "Tuvaluano/a", phoneCode: "+688" }
        ],
        [
            "UA",
            { name: "Ucrania", nationality: "Ucraniano/a", phoneCode: "+380" }
        ],
        [
            "UG",
            { name: "Uganda", nationality: "Ugandés/a", phoneCode: "+256" }
        ],
        [
            "UY",
            { name: "Uruguay", nationality: "Uruguayo/a", phoneCode: "+598" }
        ],
        [
            "UZ",
            { name: "Uzbekistán", nationality: "Uzbeko/a", phoneCode: "+998" }
        ],
        [
            "VU",
            { name: "Vanuatu", nationality: "Vanuatuense", phoneCode: "+678" }
        ],
        [
            "VA",
            { name: "Ciudad del Vaticano", nationality: "Vaticano/a", phoneCode: "+379" }
        ],
        [
            "VE",
            { name: "Venezuela", nationality: "Venezolano/a", phoneCode: "+58" }
        ],
        [
            "VN",
            { name: "Vietnam", nationality: "Vietnamita", phoneCode: "+84" }
        ],
        [
            "YE",
            { name: "Yemen", nationality: "Yemení", phoneCode: "+967" }
        ],
        [
            "ZM",
            { name: "Zambia", nationality: "Zambiano/a", phoneCode: "+260" }
        ],
        [
            "ZW",
            { name: "Zimbabue", nationality: "Zimbabuense", phoneCode: "+263" }
        ]
    ]),

    departmentsAndLocalities: new Map([
        [
            "Artigas",
            ["Artigas", "Bella Unión", "Tomás Gomensoro", "Otra localidad"]
        ],
        [
            "Canelones",
            ["Atlántida", "Barros Blancos", "Canelones", "Ciudad de la Costa", "Empalme Olmos", "Joaquín Suárez", "La Floresta", "La Paz",
            "Las Piedras", "Migues", "Pando", "Parque del Plata", "Paso Carrasco", "Progreso", "Salinas", "San Antonio",
            "San Bautista", "San Jacinto", "Santa Lucía", "Sauce", "Soca", "Tala", "Toledo", "Otra localidad"]
        ],
        [
            "Cerro Largo",
            ["Fraile Muerto", "Melo", "Río Branco", "Tupambaé", "Otra localidad"]
        ],
        [
            "Colonia",
            ["Carmelo", "Colonia del Sacramento", "Juan Lacaze", "Nueva Helvecia", "Nueva Palmira",
            "Ombúes de Lavalle", "Rosario", "Tarariras", "Otra localidad"]
        ],
        [
            "Durazno",
            ["Durazno", "Sarandí del Yí", "Otra localidad"]
        ],
        [
            "Flores",
            ["Ismael Cortinas", "Trinidad", "Otra localidad"]
        ],
        [
            "Florida",
            ["Casupá", "Florida", "Fray Marcos", "Sarandí Grande", "Otra localidad"]
        ],
        [
            "Lavalleja",
            ["José Pedro Varela", "Minas", "Solís de Mataojo", "Otra localidad"]
        ],
        [
            "Maldonado",
            ["Aiguá", "Maldonado", "Pan de Azúcar", "Piriápolis", "Punta Ballena", "Punta del Este", "San Carlos", "Otra localidad"]
        ],
        [
            "Montevideo",
            ["Montevideo", "Otra localidad"]
        ],
        [
            "Paysandú",
            ["Guichón", "Paysandú", "Quebracho", "Otra localidad"]
        ],
        [
            "Río Negro",
            ["Fray Bentos", "Nuevo Berlín", "Young", "Otra localidad"]
        ],
        [
            "Rivera",
            ["Minas de Corrales", "Rivera", "Tranqueras", "Otra localidad"]
        ],
        [
            "Rocha",
            ["Castillos", "Chuy", "La Paloma", "Lascano", "Rocha", "Otra localidad"]
        ],
        [
            "Salto",
            ["Belén", "Salto", "Otra localidad"]
        ],
        [
            "San José",
            ["Ciudad del Plata", "Ecilda Paullier", "Libertad", "Rodríguez", "San José de Mayo", "Otra localidad"]
        ],
        [
            "Soriano",
            ["Cardona", "Dolores", "Mercedes", "Palmitas", "Otra localidad"]
        ],
        [
            "Tacuarembó",
            ["Paso de los Toros", "San Gregorio de Polanco", "Tacuarembó", "Otra localidad"]
        ],
        [
            "Treinta y Tres",
            ["Santa Clara de Olimar", "Treinta y Tres", "Vergara", "Otra localidad"]
        ]
    ]),

    loadNationalitiesSelect: function(item) {
        this.countriesInformation.forEach(country => {

            const option = document.createElement("option")
            option.textContent = country.nationality
            option.value = country.nationality

            item.append(option)
        })
    },

    loadDepartmentsSelect: function(item) {
        this.departmentsAndLocalities.forEach((value, key) => {
            const option = document.createElement("option")
            option.textContent = key
            option.value = key

            item.append(option)
        })
    },

    loadLocationsSelect: function(item, department) {

        if (department.value.trim() === "") {
            
            alert("Debe seleccionar un departamento válido")
            item.innerHTML = '<option value="">Seleccione...</option>'
            item.classList.remove("valid", "invalid")
            return
        }

        item.innerHTML = '<option value="">Seleccione...</option>'
        this.departmentsAndLocalities.get(department.value).forEach(location => {

            const option = document.createElement("option")
            option.textContent = location
            option.value = location

            item.append(option)
        })
    },

    loadPhoneCodesSelect: function(item) {
        this.countriesInformation.forEach(country => {

            const phoneContent = `${country.name} (${country.phoneCode})`
            const option = document.createElement("option")
            option.textContent = phoneContent
            option.value = country.phoneCode

            item.append(option)
        })
    },

    setInvalid: (item, container, message) => {

        container.textContent = `❌️ ${message}`
        item.classList.remove("valid")
        item.classList.add("invalid")
        return false
    },

    setValid: (item, container) => {
        
        container.textContent = ""
        item.classList.remove("invalid")
        item.classList.add("valid")
        return true
    },

    registerValidator: {

        stringsInput: (item, container) => {
            const stringRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9.,_\/\s-]{4,50}$/u
            const val = item.value.trim()

            if (!stringRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Debe contener entre 4 y 50 caracteres")
            }
            return formTools.setValid(item, container)
        },

        selectsInput: (item, container) => {
            const val = item.value.trim()

            if (val === "") {

                return formTools.setInvalid(item, container,
                    "Debe seleccionar una opción válida")
            }
            return formTools.setValid(item, container)
        },

        namesInput: (item, container) => {
            const nameRegex = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]{1,49}(?: [A-ZÁÉÍÓÚÑ][a-záéíóúñ]{1,49})*$/
            const val = item.value.trim()

            if (!nameRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Cada palabra debe comenzar con mayúscula y contener solo letras.")
            }
            return formTools.setValid(item, container)
        },

        dateInput: (item, container) => {
            const dateSelected = new Date(item.value)
            dateSelected.setHours(0, 0, 0, 0)

            const today = new Date()
            today.setHours(0, 0, 0, 0)

            if (isNaN(dateSelected.getTime()) || dateSelected > today) {

                return formTools.setInvalid(item, container,
                    "La fecha no puede ser futura ni estar vacia")
            }
            return formTools.setValid(item, container)
        },

        phoneNumberInput: (item, container) => {
            const phoneRegex = /^\d{8,15}$/
            const val = item.value.trim()

            if (!phoneRegex.test(val)) { 

                return formTools.setInvalid(item, container,
                    "Debe contener entre 8 y 15 dígitos") 
            }
            return formTools.setValid(item, container)
        },

        documentIdInput: (item, container) => {
            const documentRegex = /^\d{8}$/
            const val = item.value.trim()

            if (!documentRegex.test(val)) {
                
                return formTools.setInvalid(item, container,
                    "Debe ingresar 8 números sin puntos ni guiones")
            }
            return formTools.setValid(item, container)
        },

        emailInput: (item, container) => {
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
            const val = item.value.trim()

            if (!emailRegex.test(val)) {
                
                return formTools.setInvalid(item, container,
                    "Debe ingresar un email válido. Ejemplo: example@example.ex")
            }
            return formTools.setValid(item, container)
        },

        doorNumberInput: (item, container) => {
            const doorNumberRegex = /^\d+[a-zA-Z0-9\s\/-]{0,19}$/
            const val = item.value.trim()
            
            if (!doorNumberRegex.test(val)) {
                
                return formTools.setInvalid(item, container,
                    "Debe contener entre 1 y 20 caracteres")
            }
            return formTools.setValid(item, container)
        },

        fullPhoneNumber: (itemCode, itemNumber) => {
            const fullPhone = itemCode.value + itemNumber.value.trim()
            const phone = parsePhoneNumberFromString(fullPhone)

            if (!phone || !phone.isValid()) {
                return false
            }
            return true
        },

        passwordInput: (item, container) => {
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}$/
            const val = item.value.trim()

            if (!passwordRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Debe tener entre 8 y 20 caracteres e incluir al menos una mayúscula, una minuscula y un número") 
            }
            return formTools.setValid(item, container)
        },

        passwordMatch: (item, container, otherItem) => {
            const otherVal = otherItem.value.trim()
            const val = item.value.trim()

            if (otherVal !== val) {

                return formTools.setInvalid(item, container,
                    "Las contraseñas no coinciden")
            }
            return formTools.setValid(item, container)
        },

        localitySelect: (item, container, otherItem, otherContainer) => {
            const val = item.value.trim()

            if (val === "Otra localidad") {

                otherContainer.classList.remove("hidden")
                otherItem.required = true
            } else {

                otherContainer.classList.add("hidden")
                otherItem.classList.remove("valid", "invalid")
                otherItem.required = false;
                otherItem.value = "";
            }

            if (val === "") {

                return formTools.setInvalid(item, container,
                    "Debe seleccionar una opción válida")
            }
            return formTools.setValid(item, container)
        },

        largeStringsInput: (item, container) => {
            const stringRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9.,_\/\s-]{4,150}$/u
            const val = item.value.trim()

            if (!stringRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Debe contener entre 4 y 150 caracteres")
            }
            return formTools.setValid(item, container)
        },

        idElementInput: (item, container) => {
            const idRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9\/-]{4,8}$/u
            const val = item.value.trim()

            if (!idRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Debe contener entre 4 y 8 caracteres, incluyendo al menos una letra y un número")
            }
            return formTools.setValid(item, container)
        },

        idAmbulanceInput: (item, container) => {
            const idRegex = /^[A-Z]{3}\d{4}$/u
            const val = item.value.trim()

            if (!idRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Debe contener entre 7 caracteres, incluyendo 3 mayusculas y 4 números")
            }
            return formTools.setValid(item, container)
        },

        yearInput: (item, container) => {
            const idRegex = /^\d{4}$/u
            const val = item.value.trim()

            if (!idRegex.test(Number(val)) || item.value.trim() < 1900 || item.value.trim() > 2100) {

                return formTools.setInvalid(item, container,
                    "Debe contener 7 caracteres, incluyendo 3 mayusculas y 4 números")
            }
            return formTools.setValid(item, container)
        },

        sampleCodeInput: (item, container) => {
            const idRegex = /^[A-Z]{1}\d{7}$/u
            const val = item.value.trim()

            if (!idRegex.test(val)) {

                return formTools.setInvalid(item, container,
                    "Debe contener 8 caracteres, comenzado con 1 mayusculas y después 7 números")
            }
            return formTools.setValid(item, container)
        }

    },

    loginValidator: {

        idEmployeeInput: (item, container) => {
            const idEmployeeRegex = /^[A-Z]{2}\d{8}$/
            const val = item.value.trim()

            if (!idEmployeeRegex.test(val)) { 

                return formTools.setInvalid(item, container,
                    "Ejemplo: AD31352892") 
            }
            return formTools.setValid(item, container)
        },

        emptyInput: (item, container) => {
            const val = item.value.trim()

            if (val === "") {

                return formTools.setInvalid(item, container,
                    "Este campo no puede estar vacío") 
            }
            return formTools.setValid(item, container)
        }
    }

}

export default formTools