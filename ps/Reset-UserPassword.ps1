param(
    [string]$UserAccount
)

Import-Module ActiveDirectory

# Written By: http://vcloud-lab.com
# Date: 19 January 2022
# Env: Powershell 5.1, PHP (latest), JQuery (latest), HTML 5, CSS, XAMPP

#$userAccount = 'a1'

$passwordLenght = 18
Add-Type -AssemblyName System.Web
$password = [System.Web.Security.Membership]::GeneratePassword($passwordLenght,1)
$encryptedPassword = ConvertTo-SecureString $password -AsPlainText -Force

try {
    $userInfo = Get-ADUser -Identity $UserAccount -Properties LockedOut -ErrorAction Stop
    $htmlReport = "<h3>User account '$UserAccount' found in AD</h3>`n"
    Set-ADAccountPassword -Identity $UserAccount -NewPassword $encryptedPassword -Reset -ErrorAction Stop
    $htmlReport += "<h2>New password is:</h2> <h1 style='color: black;'>$password</h1>`n"
    Set-ADUser -Identity $UserAccount -ChangePasswordAtLogon $true -ErrorAction Stop
    
    if (!$userInfo.LockedOut)
    {
        Unlock-ADAccount -Identity $UserAccount -ErrorAction Stop
        $htmlReport += "<h3>User account is unlocked</h3>`n"
    }
    $htmlReport 
    $htmlReport | Out-File c:\temp\logs.txt -Append
}
catch {
    "<h3>User $UserAccount Password Reset failed`n</h3>"
    $htmlReport | Out-File c:\temp\logs.txt -Append
}