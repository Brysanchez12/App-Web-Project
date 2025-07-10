var Meses;
var Dias;
Meses = {
  1: 'Enero', 2: 'Febrero', 3: 'Marzo',
  4: 'Abril', 5: 'Mayo', 6: 'Junio',
  7: 'Julio', 8: 'Agosto', 9: 'Septiembre',
  10: 'Octubre', 11: 'Noviembre', 12: 'Diciembre'
};
Dias = {
  1: 'Domingo', 2: 'Lunes', 3: 'Martes',
  4: 'Miércoles', 5: 'Jueves',
  6: 'Viernes', 7: 'Sábado'
};

$(document).ready(function () {
  var interval_Hora = setInterval(Horagm, 1000);
})

// Numeric
function twoDigitPad(num) {
  return num < 10 ? "0" + num : num;
}

// Set date format
function formatDate(date, patternStr) {
  //console.log(formatDate(new Date()));
  //console.log(formatDate(new Date(), 'dd-MMM-yyyy')); //OP's request
  //console.log(formatDate(new Date(), 'EEEE, MMMM d, yyyy HH:mm:ss.S aaa'));
  //console.log(formatDate(new Date(), 'EEE, MMM d, yyyy HH:mm'));
  //console.log(formatDate(new Date(), 'yyyy-MM-dd HH:mm:ss.S'));
  //console.log(formatDate(new Date(), 'M/dd/yyyy h:mmaaa'));
  if (!patternStr) {
    patternStr = 'M/d/yyyy';
  }

  let day = date.getDate(),
    month = date.getMonth(),
    year = date.getFullYear(),
    hour = date.getHours(),
    minute = date.getMinutes(),
    second = date.getSeconds(),
    miliseconds = date.getMilliseconds(),
    h = hour % 12,
    hh = twoDigitPad(h),
    HH = twoDigitPad(hour),
    mm = twoDigitPad(minute),
    ss = twoDigitPad(second),
    aaa = hour < 12 ? 'AM' : 'PM',
    DDD = Dias[date.getDay() + 1],
    D = DDD.substr(0, 3),
    dd = twoDigitPad(day),
    M = month + 1,
    MM = twoDigitPad(M),
    MMMM = Meses[month + 1],
    MMM = MMMM.substr(0, 3),
    yyyy = year + "",
    yy = yyyy.substr(2, 2);

  // checks to see if month name will be used
  patternStr = patternStr
    .replace('hh', hh).replace('h', h)
    .replace('HH', HH).replace('H', hour)
    .replace('mm', mm).replace('m', minute)
    .replace('ss', ss).replace('s', second)
    .replace('S', miliseconds)
    .replace('dd', dd).replace('d', day)
    .replace('DDD', DDD).replace('D', D)
    .replace('yyyy', yyyy)
    .replace('yy', yy)
    .replace('aaa', aaa);
  if (patternStr.indexOf('MMM') > -1) {
    patternStr = patternStr
      .replace('MMMM', MMMM)
      .replace('MMM', MMM);
  } else {
    patternStr = patternStr
      .replace('MM', MM)
      .replace('M', M);
  }
  return patternStr;
}

// Fecha en header
function Horagm() {
  const date = new Date();
  const ancho = screen.width;
  let fecha, hora;
  fecha = formatDate(date, 'D. MMM d, yyyy');
  hora = formatDate(date, 'HH:mm');
  let sfecha;
  if (ancho <= 950) {
    sfecha = hora;
  } else {
    sfecha = fecha + ' ' + hora;
  }
  const id_hora = document.getElementById('fecha');
  const span = document.createElement('span');
  span.textContent = sfecha;
  id_hora.innerHTML = ''; // Limpiar el contenido existente
  id_hora.appendChild(span);
}

/**
 * Verifica si el usuario está autenticado validando la existencia y vigencia del token en localStorage.
 *
 * @function
 * @returns {boolean} Retorna `true` si el usuario tiene una sesión válida, de lo contrario `false`.
 *
 * @description
 * Esta función:
 * - Revisa si existen los elementos `auth_token` y `auth_expira` en localStorage.
 * - Compara la fecha de expiración con la hora actual.
 * - Si no hay token o ya expiró, limpia localStorage y redirige al login.
 *
 */
function checkAuth() {
  const token = localStorage.getItem('auth_token');
  const expira = localStorage.getItem('auth_expira');

  // Si falta el token o la fecha de expiración, redirige
  if (!token || !expira) {
    redirectToLogin('Debes iniciar sesión.');
    return false;
  }

  const expiraTime = new Date(expira).getTime();
  const now = Date.now();

  // Si la sesión ha expirado
  if (now > expiraTime) {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_expira');
    Swal.fire({
      icon: 'warning',
      title: 'Sesión inválida',
      text: 'Tu sesión ha expirado.',
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false,
      willClose: () => {
        window.location.href = '/login.html';
      }
    });
    return false;
  }
  // Sesión válida
  return true;
}

/**
 * Cierra la sesión del usuario tras una confirmación con SweetAlert2.
 *
 * @function
 * @description
 * Muestra una alerta de confirmación antes de cerrar sesión. 
 * Si el usuario confirma, se eliminan los datos de autenticación del `localStorage` 
 * y se redirige al login.
 */
function logoutSession() {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '¿Deseas cerrar tu sesión?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, cerrar sesión',
    cancelButtonText: 'Cancelar',
    reverseButtons: true, // cambia el orden para enfatizar "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('auth_expira');
      window.location.href = 'login.html';
    }
  });
}


/**
 * Float to string
 * @param {float} valor
 * @param {int} tipo (0 Numero, 1 Currency)
 * @param {int} dec  Numero de decimales
 * @returns {string}
 */
// Float to string
function ftos(valor = 0, tipo = 1, dec = 2) {
  let valdat = '';
  if (tipo === 0) {
    valdat = new Intl.NumberFormat("en-US", { minimumFractionDigits: dec, maximumFractionDigits: dec }).format(valor);
  } else {
    valdat = new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" }).format(valor);
  }
  return valdat;
}

/*
 * Función que muestra un diálogo modal para edición o creación de datos
 * @param {string} dialogType - Tipo de diálogo: "Nuevo" o "Editar"
 * @param {object} item - Objeto con los datos del ítem actual
 * @param {string} tipo - Tipo de entidad
 * @param {object} db - Objeto de base de datos u origen de datos relacionado
 */
function showDetailsDialog(dialogType, item, tipo, db) {
  let htmlContent = '';
  let title = '';
  let rules = {}, messages = {};
  // Si es un nuevo registro, los campos son editables; si no, algunos serán de solo lectura
  const readonlyAttr = dialogType === "Nuevo" ? '' : 'readonly';

  // Según el tipo de entidad, se arma el contenido HTML del formulario
  switch (tipo) {
    case "producto":
      htmlContent = `        
      <form id="globalForm">
        <div class="form-group">
          <label for="nombre">Descripción</label>
          <input type="text" id="nombre" name="nombre" class="form-control"
                 value="${item.nombre || ''}"
                 placeholder="Ingrese la descripción" maxlength="100">
        </div>

        <div class="form-group">
          <label for="refere">Referencia</label>
          <input type="text" id="refere" name="refere" class="form-control col-md-6"
                 value="${item.refere || ''}"
                 placeholder="Ingrese referencia" maxlength="25">
        </div>

        <div class="form-group">
          <label for="cantid">Cantidad</label>
          <div class="input-group">
            <input type="text" id="cantid" name="cantid" data-dec="2"
                   class="form-control text-right col-md-6 solo-numeros valores"
                   value="${ftos(item.cantid || 0, 0)}"
                   placeholder="Ingrese Stock">
          </div>
        </div>

        <div class="form-group">
          <label for="costo">Precio de Costo</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="text" id="costo" name="costo" data-dec="2"
                   class="form-control text-right col-md-6 solo-numeros valores"
                   value="${ftos(item.costo || 0, 0)}"
                   placeholder="Ingrese Costo">
          </div>
        </div>

        <div class="form-group">
          <label for="prcvta">Precio de Venta</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="text" id="prcvta" name="prcvta" data-dec="2"
                   class="form-control text-right col-md-6 solo-numeros valores"
                   value="${ftos(item.prcvta || 0, 0)}"
                   placeholder="Ingrese Precio">
          </div>
        </div>
      </form>`;

      rules = {
        nombre: { required: true, minlength: 3 },
        refere: { required: false },
        costo: { required: true },
        prcvta: { required: true }
      };
      messages = {
        nombre: "Ingrese detalle del producto",
        refere: "Ingrese la referencia",
        costo: "Ingrese el costo",
        prcvta: "Ingrese el precio"
      };
      break;

    case "servicio":
      htmlContent = `        
      <form id="globalForm">
        <div class="form-group">
          <label for="nombre">Descripción</label>
          <input type="text" id="nombre" name="nombre" class="form-control"
                 value="${item.nombre || ''}"
                 placeholder="Ingrese la descripción" maxlength="100">
        </div>

        <div class="form-group">
          <label for="refere">Referencia</label>
          <input type="text" id="refere" name="refere" class="form-control col-md-6"
                 value="${item.refere || ''}"
                 placeholder="Ingrese referencia" maxlength="25">
        </div>     

        <div class="form-group">
          <label for="prcvta">Precio de Venta</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">$</span>
            </div>
            <input type="text" id="prcvta" name="prcvta" data-dec="2"
                   class="form-control text-right col-md-6 solo-numeros valores"
                   value="${ftos(item.prcvta || 0, 0)}"
                   placeholder="Ingrese Precio">
          </div>
        </div>
      </form>`;

      rules = {
        nombre: { required: true, minlength: 3 },
        refere: { required: false },
        prcvta: { required: true }
      };
      messages = {
        nombre: "Ingrese detalle del servicio",
        refere: "Ingrese la referencia",
        prcvta: "Ingrese el precio"
      };
      break;

    case "cliente":
      htmlContent = `
      <form id="globalForm">
        <div class="form-group">
          <label for="id">Cédula / RUC</label>
          <input type="text" id="id" name="id" class="form-control col-md-6 solo-numeros"
                 value="${item.id || ''}" ${readonlyAttr}
                 placeholder="Ingrese cédula o RUC" maxlength="13">
        </div>

        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" class="form-control"
                 value="${item.nombre || ''}"
                 placeholder="Ingrese el nombre completo" maxlength="50">
        </div>

        <div class="form-group">
          <label for="direcc">Dirección</label>
          <input type="text" id="direcc" name="direcc" class="form-control"
                 value="${item.direcc || ''}"
                 placeholder="Ingrese dirección" maxlength="250">
        </div>

        <div class="form-group">
          <label for="correo">Correo electrónico</label>
          <input type="email" id="correo" name="correo" class="form-control"
                 value="${item.correo || ''}"
                 placeholder="Ingrese correo: ejemplo@correo.com" maxlength="100">
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" class="form-control col-md-6 solo-numeros"
                 value="${item.telefono || ''}"
                 placeholder="Ingrese número de teléfono" maxlength="10">
        </div>
      </form>`;

      rules = {
        nombre: { required: true, minlength: 3 },
        id: { required: true, minlength: 10, maxlength: 13 },
        direcc: { required: true, minlength: 5 },
        correo: { required: true, email: true },
        telefono: { required: true, maxlength: 10 }
      };
      messages = {
        nombre: "Ingrese el nombre completo",
        id: "Ingrese una cédula o RUC válido",
        direcc: "Ingrese la dirección",
        correo: "Ingrese un correo válido",
        telefono: "Ingrese un número de teléfono válido"
      };
      break;

    case "tecnico":
      htmlContent = `
      <form id="globalForm">
        <div class="form-group">
          <label for="id">Cédula / RUC</label>
          <input type="text" id="id" name="id" class="form-control col-md-6 solo-numeros"
                 value="${item.id || ''}" ${readonlyAttr}
                 placeholder="Ingrese cédula o RUC" maxlength="13">
        </div>

        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" class="form-control"
                 value="${item.nombre || ''}"
                 placeholder="Ingrese el nombre completo" maxlength="50">
        </div>

        <div class="form-group">
          <label for="direcc">Dirección</label>
          <input type="text" id="direcc" name="direcc" class="form-control"
                 value="${item.direcc || ''}"
                 placeholder="Ingrese dirección" maxlength="250">
        </div>

        <div class="form-group">
          <label for="correo">Correo electrónico</label>
          <input type="email" id="correo" name="correo" class="form-control"
                 value="${item.correo || ''}"
                 placeholder="Ingrese correo: ejemplo@correo.com" maxlength="100">
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" class="form-control col-md-6 solo-numeros"
                 value="${item.telefono || ''}"
                 placeholder="Ingrese número de teléfono" maxlength="10">
        </div>
      </form>`;

      rules = {
        nombre: { required: true, minlength: 3 },
        id: { required: true, minlength: 3, maxlength: 10 },
        direcc: { required: true, minlength: 5 },
        correo: { required: true, email: true },
        telefono: { required: true, maxlength: 10 }
      };
      messages = {
        nombre: "Ingrese el nombre completo",
        id: "Ingrese una cédula o RUC válido",
        direcc: "Ingrese la dirección",
        correo: "Ingrese un correo válido",
        telefono: "Ingrese un número de teléfono válido"
      };
      break;
  }

  // Renderiza el contenido HTML en el div del diálogo
  $("#globalDialog").html(htmlContent).dialog({
    modal: true,
    title: title,
    width: 500,
    buttons: [
      {
        text: "Guardar",
        click: function () {
          // Validar el formulario antes de guardar
          if ($("#globalForm").valid()) {
            saveItem(item, tipo, dialogType === "Nuevo", db);
            $(this).dialog("close");
          }
        },
        class: "btn btn-primary"
      },
      {
        text: "Cancelar",
        click: function () {
          $(this).dialog("close");
        },
        class: "btn btn-secondary"
      }
    ],
    open: function () {
      // Desactiva los clics en la barra lateral para evitar errores de enfoque
      $('aside.main-sidebar').css('pointer-events', 'none');
      // Genera el título con ícono y tipo de diálogo
      const icon = dialogType === "Nuevo"
        ? '<i class="fas fa-plus-circle text-success"></i>'
        : '<i class="fas fa-edit text-primary"></i>';

      const customTitle = `${icon} ${dialogType} ${tipo.charAt(0).toUpperCase() + tipo.slice(1)}`;

      // Insertar HTML en el encabezado del diálogo
      $(this).dialog("widget").find(".ui-dialog-title").html(customTitle);
      // Validación para campos de texto (permite letras, números, tildes y ñ)
      $('#globalForm input[type="text"]').on('keydown', function (event) {
        const key = event.key;
        const isAllowed =
          /^[a-zA-Z0-9._,áéíóúÁÉÍÓÚñÑ ]$/.test(key) ||
          ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(key);

        if (!isAllowed) {
          event.preventDefault();
        }
      }).on('paste', function (event) {
        const paste = (event.originalEvent || event).clipboardData.getData('text');
        if (!/^[a-zA-Z0-9,_.áéíóúÁÉÍÓÚñÑ ]+$/.test(paste)) {
          event.preventDefault();
        }
      });

      // Validación para permitir solo números en campos específicos
      $('#globalForm input.solo-numeros')
        .on('keydown', function (event) {
          const key = event.key;
          const ctrl = event.ctrlKey || event.metaKey;
          console.log(key, ctrl);

          // Permitir: teclas de control y navegación
          if (
            ['Backspace', 'Tab', 'Enter', 'Escape', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(key) ||
            (ctrl && ['a', 'c', 'v', 'x'].includes(key.toLowerCase()))
          ) {
            return;
          }

          // Permitir números del 0 al 9
          if (!/^[0-9]$/.test(key)) {
            event.preventDefault();
          }
        })
        .on('paste', function (event) {
          const paste = (event.originalEvent || event).clipboardData.getData('text');
          if (!/^\d+$/.test(paste)) {
            event.preventDefault();
          }
        });


      // Formatea valores numéricos al salir del input
      $('#globalForm input.valores').on('blur', function () {
        const val = parseFloat($(this).val().replace(',', '.')) || 0;
        let dec = $(this).data('dec') ?? 0;
        $(this).val(ftos(val, 0, dec));
      });

      // Selecciona el contenido del input al enfocar
      $('input').on('focus', function () {
        $(this).select();
      });
    },
    close: function () {
      // Restaura los clics en la barra lateral
      $('aside.main-sidebar').css('pointer-events', 'auto');
      // Reinicia validaciones del formulario
      $("#globalForm").validate().resetForm();
      $("#globalForm").find(".error").removeClass("error");
      // Cierra y limpia el contenido del diálogo
      $(this).dialog("destroy").html("");
    }
  });

  // Aplica reglas de validación jQuery Validate al formulario
  $("#globalForm").validate({
    rules: rules,
    messages: messages
  });

}

/*
 * Guarda un ítem en la base de datos según su tipo, ya sea insertándolo o actualizándolo.
 * @param {object} item - Objeto que contiene los datos del ítem actual.
 * @param {string} tipo - Tipo de entidad 
 * @param {boolean} isNew - Indica si se trata de un nuevo ítem (true) o una edición (false).
 * @param {object} db - Objeto que contiene los métodos insertItem y updateItem, para guardar en la base de datos.
 */
function saveItem(item, tipo, isNew, db) {

  switch (tipo) {
    case "producto":
      // Actualiza los campos del objeto con los valores del formulario
      $.extend(item, {
        nombre: $("#nombre").val(),
        refere: $("#refere").val(),
        cantid: $("#cantid").val(),
        costo: $("#costo").val(),
        prcvta: $("#prcvta").val()
      });

      // Inserta o actualiza según corresponda
      if (isNew) {
        db.insertItem(item).done(function () {
          // Recarga el grid
          $("#jsgProductos").jsGrid("loadData");
        });
      } else {
        db.updateItem(item).done(function () {
          $("#jsgProductos").jsGrid("loadData");
        });
      }
      break;

    case "servicio":
      $.extend(item, {
        nombre: $("#nombre").val(),
        refere: $("#refere").val(),
        prcvta: $("#prcvta").val()
      });

      if (isNew) {
        db.insertItem(item).done(function () {
          $("#jsgServicios").jsGrid("loadData");
        });
      } else {
        db.updateItem(item).done(function () {
          $("#jsgServicios").jsGrid("loadData");
        });
      }
      break;
    case "cliente":
      $.extend(item, {
        nombre: $("#nombre").val(),
        id: $("#id").val().toString(),
        direcc: $("#direcc").val(),
        correo: $("#correo").val(),
        telefono: $("#telefono").val()
      });

      if (isNew) {
        db.insertItem(item).done(function () {
          $("#jsgClientes").jsGrid("loadData");
        });
      } else {
        db.updateItem(item).done(function () {
          $("#jsgClientes").jsGrid("loadData");
        });
      }
      break;
    case "tecnico":
      $.extend(item, {
        nombre: $("#nombre").val(),
        id: $("#id").val().toString(),
        direcc: $("#direcc").val(),
        correo: $("#correo").val(),
        telefono: $("#telefono").val()
      });

      if (isNew) {
        db.insertItem(item).done(function () {
          $("#jsgTecnicos").jsGrid("loadData");
        });
      } else {
        db.updateItem(item).done(function () {
          $("#jsgTecnicos").jsGrid("loadData");
        });
      }
      break;
  }

}
