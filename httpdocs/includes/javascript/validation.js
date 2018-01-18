/*********************************************************************
multi-language validation script
	version 2.21
	by matthew frank

There are no warranties expressed or implied.  I will not be held
responsible for any loss of data or sanity through the use or
implementation of this script.  This script may be re-used and
distrubted freely provided this header remains intact and all
supporting files are included (unaltered) in the distribution:

	validation.js   - this file
	validation.htm  - example form
	readme.htm      - directions on using this script
	(validation.zip contains all files)

If you are interested in keeping up with the latest releases of this
script or asking questions about its implementation, think about joining
the eGroups discussion forum dedicated to data validation:

	http://www.egroups.com/group/validation

*********************************************************************/


/*====================================================================
Function: Err
Purpose:  Custom object constructor
Inputs:   None
Returns:  undefined
====================================================================*/
function Err(){
	/*********************************************************************
	Method:   Err.clear
	Purpose:  Clear values from Error object
	Inputs:   none
	Returns:  undefined
	*********************************************************************/
	this.clear=function (){
		this.source=new Object;
		this.type=new Object;
		this.format=new String;
	}
	/*********************************************************************
	Method:   Err.add
	Purpose:  Adds error to Error object
	Inputs:   oSource - source element object
	          vType   - integer value of error type (or custom string)
	          sFormat - optional date format
	Returns:  undefined
	*********************************************************************/
	this.add=function (oSource,vType,sFormat){
		this.source=oSource;
		this.type=vType;
		this.format=sFormat;
	}
	/*********************************************************************
	Method:   Err.raise
	Purpose:  Gives visual warning to user about all errors contained in
	          the Error object
	Inputs:   none
	Returns:  undefined
	*********************************************************************/
	this.raise=function (){
		var oElement=this.source;
		var sLang;
		var sNym=oElement.getAttribute("nym");
		// if type is not a number, it must be a custom error message
		var sMsg=(typeof this.type=="string")?this.type:oElement.getAttribute("msg");

		oElement.paint();
		if(oElement.select)
			oElement.select();
		if(sMsg)
			alert(sMsg);
		else{
			// Walk through object hierarchy to find applicable language
			var oParent=oElement;
			sLang=oParent.getAttribute("lang").substring(0,2).toLowerCase();
			while(!sLang || !_validation.messages[sLang]){
				oParent=oParent.parentElement;
				if(oParent)
					sLang=oParent.getAttribute("lang").substring(0,2).toLowerCase();
				else
					// Default language is English
					sLang="en";
			}
			sMsg=_validation.messages[sLang][this.type];
			alert(((sNym)?sNym+": ":"")+sMsg+((this.format)
				?" "+this.format.reformat(sLang,this.type):""));
		}

		// Perform onvalidatefocus event handler for invalid field
		if(oElement.onvalidatefocus)
			oElement.onvalidatefocus();

		// Give invalid field focus
		oElement.focus();
		// Clear the Err object
		this.clear();
	}

	// Define the working object model
	this.clear();
}

/*====================================================================
Function: Validation
Purpose:  Custom object constructor.
Inputs:   None
Returns:  undefined
====================================================================*/
function Validation(){
	// Define global constants for calls to error message arrays
	this.REQUIRED = 0;
	this.INTEGER  = 1;
	this.FLOAT    = 2;
	this.DATE     = 3;
	this.AMOUNT   = 4;
	this.MASK     = 5;

	// Create error message dictionary
	this.messages = new Array;

	// Prototype the date tokens for each language
	Array.prototype.MM = new String;
	Array.prototype.DD = new String;
	Array.prototype.YYYY = new String;

	//English
	this.messages["en"]=new Array(
		"Please enter a value",
		"Please enter a valid integer",
		"Please enter a valid floating point",
		"Please enter a valid date",
		"Please enter a valid monetary amount",
		"Please enter a value in the form of ");
		with(this.messages["en"]){
			MM="MM";
			DD="DD";
			YYYY="YYYY";
		}

	//Portuguese
	this.messages["pt"]=new Array(
		"Por favor digite um valor válido",
		"Por favor digite um valor numérico inteiro",
		"Por favor digite o valor ponto flutuante",
		"Por favor digite uma data válida",
		"Por favor digite o valor monetário",
		"Por favor digite um valor no formulario ");
		with(this.messages["pt"]){
			MM="MM";
			DD="DD";
			YYYY="AAAA";
		}

	//French
	this.messages["fr"]=new Array(
		"Entrer une valeur, SVP",
		"Entrer un entier correct, SVP",
		"Entrer un point flottant correct, SVP",
		"Entrer une date correcte, SVP",
		"Entrer une valeur monetaire correcte, SVP",
		"Entrer, SVP, une valeur suivant le format suivant: ");
		with(this.messages["fr"]){
			MM="MM";
			DD="JJ";
			YYYY="AAAA";
		}

	//German
	this.messages["de"]=new Array(
		"Tragen Sie bitte einen Wert ein",
		"Tragen Sie bitte eine gültige Ganze Zahl ein",
		"Tragen Sie bitte ein Fließkomma ein",
		"Tragen Sie bitte ein gültiges Datum ein",
		"Tragen Sie bitte eine gültigen Geldbetrag ein",
		"Tragen Sie bitte einen Wert ein in Form von ");
		with(this.messages["de"]){
			MM="MM";
			DD="TT";
			YYYY="JJJJ";
		}

	//Italian
	this.messages["it"]=new Array(
		"Si prega di inserire un valore",
		"Si prega di inserire un valido numero intero",
		"Si prega di inserire un valido numero in virgola mobile",
		"Si prega di inserire una data valida",
		"Si prega di inserire una quantitá di valuta valida",
		"Si prega di inserire un valore nel formato ");
		with(this.messages["it"]){
			MM="MM";
			DD="GG";
			YYYY="AAAA";
		}

	//Spanish
	this.messages["es"]=new Array(
		"Por favor introduzca un valor",
		"Por favor introduzca un numero entero válido",
		"Por favor introduzca un punto flotante válido",
		"Por favor introduzca una fecha válida",
		"Por favor introduzca una cantidad monetaria válida",
		"Por favor introduzca un valor con el siguiente formato ");
		with(this.messages["es"]){
			MM="MM";
			DD="DD";
			YYYY="AAAA";
		}

	//Dutch
	this.messages["nl"]=new Array(
		"Vul s.v.p. een waarde in",
		"Vul s.v.p. een geldig geheel getal in",
		"Vul s.v.p. een geldig zwevend decimaal teken in",
		"Vul s.v.p. een geldige datum in",
		"Vul s.v.p. een geldig geldbedrag in",
		"Vul s.v.p. een waarde in, in de vorm van ");
		with(this.messages["nl"]){
			MM="MM";
			DD="DD";
			YYYY="JJJJ";
		}

	//Swedish
	this.messages["sv"]=new Array(
		"Var vänlig fyll i ett värde",
		"Var vänlig fyll i ett giltigt heltal",
		"Var vänlig fyll i ett giltigt tal",
		"Var vänlig fyll i ett giltigt datum",
		"Var vänlig fyll i ett giltigt belopp",
		"Var vänlig fyll i ett värde på formen ");
		with(this.messages["sv"]){
			MM="MM";
			DD="DD";
			YYYY="ÅÅÅÅ";
		}

	/*********************************************************************
	Method:   Validation.setDefault
	Purpose:  Set value for variable v if v is zero, empty string or
	          undefined
	Inputs:   v - variable (passed by value)
	          d - default value
	Returns:  v or d
	*********************************************************************/
	this.setDefault=function (v, d){
		return (v)?v:d;
	}
	/*********************************************************************
	Method:   Validation.isDate
	Purpose:  Check that value is a date of the correct format
	Inputs:   oElement - form element
	          sFormat  - string format
	Returns:  boolean
	*********************************************************************/
	this.isDate=function (oElement,sFormat){
		var sDate=oElement.value;
		var aDaysInMonth=new Array(31,28,31,30,31,30,31,31,30,31,30,31);

		// Fetch the date separator from the user's input
		var sSepDate=sDate.charAt(sDate.search(/\D/));
		// Fetch the date separator from the format
		var sSepFormat=sFormat.charAt(sFormat.search(/[^MDY]/i));
		// Compare separators
		if (sSepDate!=sSepFormat)
			return false;

		// Fetch the three pieces of the date from the user's input and the format
		var aValueMDY=sDate.split(sSepDate);
		var aFormatMDY=sFormat.split(sSepFormat);
		var iMonth,iDay,iYear;

		// Validate that all pieces of the date are numbers
		if (  !_validation.isNum(aValueMDY[0])
			||!_validation.isNum(aValueMDY[1])
			||!_validation.isNum(aValueMDY[2]))
			return false;

		// Assign day, month, year based on format
		switch (aFormatMDY[0].toUpperCase()){
			case "YYYY" :
				iYear=aValueMDY[0];
				break;
			case "DD" :
				iDay=aValueMDY[0];
				break;
			case "MM" :
				iMonth=aValueMDY[0];
				break;
			default :
				return false;
		}
		switch (aFormatMDY[1].toUpperCase()){
			case "YYYY" :
				iYear=aValueMDY[1];
				break;
			case "MM" :
				iMonth=aValueMDY[1];
				break;
			case "DD" :
				iDay=aValueMDY[1];
				break;
			default :
				return false;
		}
		switch(aFormatMDY[2].toUpperCase()){
			case "MM" :
				iMonth=aValueMDY[2];
				break;
			case "DD" :
				iDay=aValueMDY[2];
				break;
			case "YYYY" :
				iYear=aValueMDY[2];
				break;
			default :
				return false;
		}

		// Require 4 digit year
		if(oElement.form.getAttribute("year4")!=null && iYear.length!=4)
			return false;
		// Process pivot date and update field
		var iPivot=_validation.setDefault(oElement.getAttribute("pivot"),
			oElement.form.getAttribute("pivot"));
		if(iPivot && iPivot.length==2 && iYear.length==2){
			iYear=((iYear>iPivot)?19:20).toString()+iYear;
			var sValue=aFormatMDY.join(sSepFormat).replace(/MM/i,iMonth);
			sValue=sValue.replace(/DD/i,iDay).replace(/YYYY/i,iYear);
			oElement.value=sValue;
		}

		// Check for leap year
		var iDaysInMonth=(iMonth!=2)?aDaysInMonth[iMonth-1]:
			((iYear%4==0 && iYear%100!=0 || iYear % 400==0)?29:28);

		return (iDay!=null && iMonth!=null && iYear!=null
				&& iMonth<13 && iMonth>0 && iDay>0 && iDay<=iDaysInMonth);
	}
	/********************************************
	Method:   Validation.isNum
	Purpose:  Check that parameter is a number
	Inputs:   v - string value
	Returns:  boolean
	********************************************/
	this.isNum=function (v){
		return (typeof v!="undefined" && v.toString() && !/\D/.test(v));
	}
	/*********************************************************************
	Method:   Validation.setup
	Purpose:  Set up methods and event handlers for all forms and elements
	Inputs:   none
	Returns:  undefined
	*********************************************************************/
	this.setup=function (){
		// Fan through forms on page to perform initializations
		var i,iForms=document.forms.length;
		for(i=0; i<iForms; i++){
			var oForm=document.forms[i];
			if(!oForm.bProcessed){
				/*********************************************
				Method:   Form.markRequired
				Purpose:  Mark all required fields for a form
				Inputs:   none
				Returns:  undefined
				*********************************************/
				oForm.markRequired=function (){
					var i, iElements=this.elements.length;
					var sMarkHTML, sMarkWhere;
					for(i=0; i<iElements; i++){
						var oElement=this.elements[i];
						// Perform onmark event handler
						if(oElement.onmark && oElement.onmark()==false)
							continue;
						if(oElement.getAttribute("required")!=null){
							sMarkHTML=this.getAttribute("insert");
							sMarkWhere=this.getAttribute("mark");
							if(sMarkHTML){
								switch(sMarkWhere.toLowerCase()){
									case "before" :
										sMarkWhere="beforeBegin";
										break;
									default :
										sMarkWhere="afterEnd";
								}
								oElement.insertAdjacentHTML(sMarkWhere,sMarkHTML);
							}else{
								var sClassName=oElement.className;
								if(sClassName!="required"){
									oElement.setAttribute("nonreqClass",oElement.className);
									oElement.className="required";
								}else{
									oElement.className=_validation.setDefault(oElement.getAttribute("nonreqClass"),oElement.className);
									oElement.removeAttribute("nonreqClass");
								}
							}
						}
					}
				}
				var sValidateWhen=oForm.getAttribute("validate");
				if (sValidateWhen!=null){
					//
					// Capture and replace onreset and onsubmit event handlers
					//
					oForm.fSubmit=oForm.onsubmit;
					oForm.fReset=oForm.onreset;

					// Create new event handlers
					oForm.onsubmit=function (){
						var i, oElement, iElements=this.elements.length;
						// Restore all elements to original style
						for (i=0; i<iElements; i++)
							this.elements[i].restore();
						// Validate individual elements
						for(i=0;i<iElements;i++){
							oElement=this.elements[i];
							// Perform default validation for element
							if (!oElement.valid()){
								_err.raise();
								event.returnValue=false;
								return;
							}
						}

						// Perform original onsubmit event handler
						if (this.fSubmit && this.fSubmit()==false){
							event.returnValue=false;
							return;
						}

						// Insert default values just before submit
						var vDefault;
						for(i=0;i<iElements;i++){
							oElement=this.elements[i];
							vDefault=oElement.getAttribute("default");
							if(vDefault && !oElement.value)
								oElement.value=vDefault;
						}
					}
					oForm.onreset=function (){
						var i, iElements=this.elements.length;
						for (i=0; i<iElements; i++)
							this.elements[i].restore();
						// Perform original event handler if present
						if (this.fReset && this.fReset()==false)
								event.returnValue=false;
					}
				}
				oForm.bProcessed=true;
			}
			// Create Input methods
			var j, iElements=oForm.elements.length;
			for(j=0; j<iElements; j++){
				var oElement=oForm.elements[j];
				if(!oElement.bProcessed) {

					// All event handlers are presumed to be strings/functions
					// at parse-time and assigned only as functions at run-time.

					// Create custom onvalidate event handlers
					var vOnValidate=oElement.getAttribute("onvalidate");
					if(vOnValidate){
						if(typeof vOnValidate!="function")
							oElement.onvalidate=new Function(vOnValidate);
						else
							oElement.onvalidate=vOnValidate;
					}
					// Create custom handler for onvalidatefocus event
					var vOnValidateFocus=oElement.getAttribute("onvalidatefocus");
					if(vOnValidateFocus){
						if(typeof vOnValidateFocus!="function")
							oElement.onvalidatefocus=new Function(vOnValidateFocus);
						else
							oElement.onvalidatefocus=vOnValidateFocus;
					}
					// Custom onmark event handler
					var vOnMark=oElement.getAttribute("onmark");
					if(vOnMark){
						if(typeof vOnMark!="function")
							oElement.onmark=new Function(vOnMark);
						else
							oElement.onmark=vOnMark;
					}
					// Custom onkeypress filtering for text fields
					if(oElement.onkeypress)
						oElement.fKeypress=oElement.onkeypress;
					oElement.onkeypress=function (){
						if(this.fKeypress && this.fKeypress()==false)
							event.returnValue=false;
						var sFilter=this.getAttribute("filter");
						if(sFilter){
							var sKey=String.fromCharCode(event.keyCode);
							var re=new RegExp(sFilter);
							// Do not filter out ENTER!
							if(sKey!="\r" && !re.test(sKey))
								event.returnValue=false;
							event.keyCode=sKey.charCodeAt(0);
						}
					}
					// Custom onchange validation
					if(sValidateWhen=="onchange") {
						// Capture and replace onchange event handlers
						if(oElement.onchange)
							oElement.fChange=oElement.onchange;
						oElement.onchange=function (){
							this.restore();
							if(!this.valid()){
								_err.raise();
								event.returnValue=false;
							}
							if(this.fChange && this.fChange()==false){
								event.returnValue=false;
							}
						}
					}
					/***********************************
					Method:   Input.paint
					Purpose:  Change style of element
					Inputs:   none
					Returns:  undefined
					***********************************/
					oElement.paint=function(){
						var sColor = _validation.setDefault(
							this.getAttribute("invalidColor"),
							this.form.getAttribute("invalidColor") );
						if (!sColor){
							// Paint element by changing class
							this.setAttribute("ol");
						}
					}
				}
			}
		}
	}
}

