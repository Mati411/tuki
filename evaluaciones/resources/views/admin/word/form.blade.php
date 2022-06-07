@extends('layouts.admin')

@section('content')
<section>
  <div class="container">
    <h1 class="text-center" style="margin-bottom: 50px">Exportar informe</h1>

    <form method="POST">
        @csrf
        <div class="form-group">
            <label for="inputDate">Fecha del reporte</label>
            <input name="date" type="date" class="form-control" id="inputDate" value="{{ date('Y-m-d')}}" required />
        </div>
        <div class="form-group">
            <label for="inputPlace">Lugar</label>
            <input name="place" type="text" class="form-control" id="inputPlace" value="Ciudad autónoma de Buenos Aires" required />
        </div>

        <hr />

        <div class="form-group">
            <label for="inputClient">Nombre del cliente</label>
            <input name="client" type="text" class="form-control" id="inputClient" value="" required />
        </div>

        <div class="form-group">
            <label for="inputFullname">Nombre y apellido del evaluado</label>
            <input name="fullname" type="text" class="form-control" id="inputFullname" value="" required />
        </div>
        <div class="form-group">
            <label for="inputAge">Edad</label>
            <input name="age" type="number" class="form-control" id="inputAge" min="13" max="110" value="" required />
        </div>
        <div class="form-group">
            <label for="inputDNI">DNI</label>
            <input name="dni" type="number" class="form-control" id="inputDNI" value="" required />
        </div>

        <hr />

        <div class="form-group">
            <label>La entrevista se realizó de forma remota?</label>
            <div class="radio">
                <label>
                    <input type="radio" name="remote" id="optionsGender" value="1" required selected />
                    Sí
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="remote" id="optionsGender" value="0" required />
                    No
                </label>
            </div>
        </div>

        <hr />

        <div class="form-group">
            <label for="inputReason">Motivo</label>
            <textarea name="reason" class="form-control wysiwyg" id="inputReason" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label for="inputInterview">Desarrollo de la entrevista</label>
            <textarea name="interview" class="form-control wysiwyg" id="inputInterview" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label for="inputHistory">Historia de la Enfermedad Actual – Antecedentes por salud mental</label>
            <textarea name="history" class="form-control wysiwyg" id="inputHistory" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label for="inputLevels">Niveles de funcionamiento</label>
            <textarea name="levels" class="form-control wysiwyg" id="inputLevels" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label for="inputSocial">Datos pertinentes de su historia personal y social</label>
            <textarea name="social" class="form-control wysiwyg" id="inputSocial" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label for="inputClinicalHistory">Antecedentes Clínicos</label>
            <textarea name="clinical_history" class="form-control wysiwyg" id="inputClinicalHistory" rows="6"></textarea>
        </div>

        <hr />

        <div class="form-group">
            <label for="inputPsychopathological">Exploración Psicopatológica</label>
            <textarea name="psychopathological" class="form-control wysiwyg" id="inputPsychopathological" rows="6"></textarea>
        </div>

        <hr />


        <h3>Diagnostico presuntivo</h3>
        <div class="form-group">
            <label for="inputDiagnosis1">Eje I - Trastornos Mentales</label>
            <input name="diagnosis[1]" type="text" class="form-control" id="inputDiagnosis1" value="" />
        </div>
        <div class="form-group">
            <label for="inputDiagnosis2">Eje II - Trastornos de la Personalidad</label>
            <input name="diagnosis[2]" type="text" class="form-control" id="inputDiagnosis2" value="" />
        </div>
        <div class="form-group">
            <label for="inputDiagnosis3">Eje III - Enfermedades médicas</label>
            <input name="diagnosis[3]" type="text" class="form-control" id="inputDiagnosis3" value="" />
        </div>
        <div class="form-group">
            <label for="inputDiagnosis4">Eje IV - Problemas Socio-ambientales</label>
            <input name="diagnosis[4]" type="text" class="form-control" id="inputDiagnosis4" value="" />
        </div>
        <div class="form-group">
            <label for="inputDiagnosis5">Eje V - Escala Valoración Global</label>
            <input name="diagnosis[5]" type="text" class="form-control" id="inputDiagnosis5" value="" />
        </div>

        <hr />

        <div class="form-group">
            <label for="inputConsiderations">Consideraciones Laborales</label>
            <textarea name="considerations" class="form-control wysiwyg" id="inputConsiderations" rows="6"></textarea>
        </div>

        <hr />

        <h3>Firmas</h3>
        <div class="checkbox">
          <label>
            <input name="signatures[1]" type="checkbox" value="">
            Lic. Gabriela C. Kardos
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input name="signatures[2]" type="checkbox" value="">
            Dr. Miguel Vallejos
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input name="signatures[3]" type="checkbox" value="">
            Dr. Matias Salvador Bertone
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input name="signatures[4]" type="checkbox" value="">
            Dr. Joaquin Lopez Regueira
          </label>
        </div>
        <div class="checkbox">
          <label>
            <input name="signatures[5]" type="checkbox" value="">
            Dr. Alejandro Elman Perahia
          </label>
        </div>



        <div class="text-center" style="margin-top: 40px">
          <button class="btn btn-primary" type="submit">Generar informe</button>
        </div>
    </form>
  </div>
</section>

<script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
    var targets = document.querySelectorAll('.wysiwyg');
    for (var i = 0; i < targets.length; i++) {
        var elm = targets[i];
        ClassicEditor
            .create(elm, {
                fontSize: {
                    options: [10]
                }
            })
            .catch(error => {
                console.error(error);
            });
    }
});

</script>
@endsection
