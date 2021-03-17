<html>
  <head>
    <title>HTML email template</title>
    <meta name="viewport" content="width = 375, initial-scale = -1">
  </head>

  <body style="background-color: #ffffff; font-size: 16px;">
    <center>
      <table align="center" border="0" cellpadding="0" cellspacing="0" style="height:100%; width:600px;">
          <!-- BEGIN EMAIL -->
          <tr>
            <td align="center" bgcolor="#ffffff" style="padding:30px">
               <p style="text-align:left">Hallo,<br><br> Kami menerima permintaan reset password untuk akun email ini pada Sistem penerimaan mahasiswa baru Pascasarjana UIN SUSKA RIAU. Untuk melanjutkan proses reset password, klik tombol reset password dibawah ini.
              </p>
              {{-- <p>
                <a target="_blank" style="text-decoration:none; background-color: black; border: black 1px solid; color: #fff; padding:10px 10px; display:block;" href="{{ protocol }}://{{ domain }}{% url 'password_reset_confirm' uidb64=uid token=token %}">
                  <strong>Reset Password</strong></a>
              </p> --}}
              <div class="card-body">
                <a style="text-decoration:none; background-color: black; border: black 1px solid; color: #fff; padding:10px 10px; display:block;" href="http://pps-pmb.test/reset-password/{{$token}}"><strong>Reset Password</strong></a>.
            </div>
              <p style="text-align:left">Link reset password ini hanya dapat digunakan sekali. Jika anda ingin melakukan reset password kembali anda dapat mengunjungi website pmb-pasca.uin-suska.ac.id dan mengajukan reset password ulang.<br><br>Jika anda tidak melakukan permintaan reset password, anda dapat mengabaikan email ini.</p>
              <p style="text-align:left">Terimakasih.
                <br>Tim Pascasarjana UIN SUSKA RIAU
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </center>
  </body>
</html>
