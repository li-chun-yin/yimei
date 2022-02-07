const { app, BrowserWindow, Menu } = require('electron')

Menu.setApplicationMenu(null) // 隐藏菜单栏

function createWindow () {

  const win = new BrowserWindow({
    title: '一媒工具'
  })

  win.loadFile('./dist/index.html')

  win.maximize()
}

app.whenReady().then(() => {
  createWindow()
  app.on('activate', function () {
    if (BrowserWindow.getAllWindows().length === 0) createWindow()
  })
})

app.on('window-all-closed', function () {
  if (process.platform !== 'darwin') app.quit()
})

app.commandLine.appendSwitch('ignore-certificate-errors')    //忽略证书的检测
app.commandLine.appendSwitch('allow-file-access-from-files', true)