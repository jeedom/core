{
  serverAddr: $relay, serverPort: .port,
  user: .sessionId, clientID: .sessionId,
  loginFailExit: true,
  log: {to: "console", level: "warn", disablePrintColor: true},
  transport: {
    protocol: "tcp", poolCount: 1,
    dialServerTimeout: 5,
    heartbeatInterval: 10, heartbeatTimeout: 30,
    tls: {enable: true, serverName: $relay, trustedCaFile: $ca}
  },
  auth: {
    method: "oidc", additionalScopes: ["HeartBeats", "NewWorkConns"],
    oidc: {
      clientID: .sessionId, clientSecret: .sessionSecret,
      audience: .audience, tokenEndpointURL: (.issuer + "/token"),
      trustedCaFile: $ca, insecureSkipVerify: false
    }
  },
  proxies: [{name: "web", type: "http", localIP: "127.0.0.1", localPort: 80, customDomains: [.domain]}]
}
